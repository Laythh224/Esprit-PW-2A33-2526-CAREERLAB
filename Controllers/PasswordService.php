<?php

class PasswordService
{
    private PasswordResetModel $model;
    private Mailer $mailer;
    private int $maxAttempts;
    private int $ttlSeconds;
    private int $resendCooldownSeconds;

    public function __construct(
        PasswordResetModel $model,
        Mailer $mailer,
        int $maxAttempts = 3,
        int $ttlSeconds = 600,
        int $resendCooldownSeconds = 60
    ) {
        $this->model = $model;
        $this->mailer = $mailer;
        $this->maxAttempts = $maxAttempts;
        $this->ttlSeconds = $ttlSeconds;
        $this->resendCooldownSeconds = $resendCooldownSeconds;
    }

    public function requestReset(string $email): array
    {
        $this->model->purgeExpired();
        $account = $this->model->findAccountByEmail($email);

        if ($account === null) {
            return [
                'ok' => true,
                'message' => 'Si un compte existe avec cet email, un code de verification a ete envoye.',
            ];
        }

        $code = $this->generateCode();
        $expiresAt = date('Y-m-d H:i:s', time() + $this->ttlSeconds);
        $lastSentAt = date('Y-m-d H:i:s');
        $codeHash = password_hash($code, PASSWORD_DEFAULT);

        $existing = $this->model->findResetByEmail($email);
        if ($existing !== null && $this->resendWaitSeconds($existing) > 0) {
            return [
                'ok' => true,
                'message' => 'Si un compte existe avec cet email, un code de verification a ete envoye.',
            ];
        }

        if ($existing === null) {
            $this->model->upsertResetRequest($email, $codeHash, $expiresAt, $lastSentAt);
        } else {
            $this->model->markResent($email, $codeHash, $expiresAt, $lastSentAt);
        }

        try {
            $this->mailer->sendPasswordResetCode($email, (string) $account['display_name'], $code, (int) floor($this->ttlSeconds / 60));
        } catch (Throwable $exception) {
            throw new RuntimeException("L'envoi de l'email a echoue. Veuillez reessayer plus tard.");
        }

        return [
            'ok' => true,
            'message' => 'Si un compte existe avec cet email, un code de verification a ete envoye.',
        ];
    }

    public function resendCode(string $email): array
    {
        $this->model->purgeExpired();
        $account = $this->model->findAccountByEmail($email);
        $reset = $this->model->findResetByEmail($email);

        if ($account === null || $reset === null) {
            return [
                'ok' => true,
                'message' => 'Si un compte existe avec cet email, un code de verification a ete envoye.',
            ];
        }

        $remaining = $this->resendWaitSeconds($reset);
        if ($remaining > 0) {
            return [
                'ok' => false,
                'message' => 'Veuillez patienter ' . $remaining . ' secondes avant de renvoyer le code.',
            ];
        }

        $code = $this->generateCode();
        $expiresAt = date('Y-m-d H:i:s', time() + $this->ttlSeconds);
        $lastSentAt = date('Y-m-d H:i:s');
        $codeHash = password_hash($code, PASSWORD_DEFAULT);
        $this->model->markResent($email, $codeHash, $expiresAt, $lastSentAt);

        try {
            $this->mailer->sendPasswordResetCode($email, (string) $account['display_name'], $code, (int) floor($this->ttlSeconds / 60));
        } catch (Throwable $exception) {
            throw new RuntimeException("L'envoi de l'email a echoue. Veuillez reessayer plus tard.");
        }

        return [
            'ok' => true,
            'message' => 'Un nouveau code a ete envoye a votre email.',
        ];
    }

    public function verifyCode(string $email, string $code): array
    {
        $this->model->purgeExpired();
        $reset = $this->model->findResetByEmail($email);

        if ($reset === null) {
            return ['ok' => false, 'message' => 'Code invalide ou expire.'];
        }

        if (strtotime((string) $reset['expires_at']) < time()) {
            $this->model->deleteResetRequest($email);
            return ['ok' => false, 'message' => 'Code invalide ou expire.'];
        }

        if ((int) ($reset['attempts'] ?? 0) >= $this->maxAttempts) {
            return ['ok' => false, 'message' => 'Trop de tentatives. Veuillez renvoyer un nouveau code.'];
        }

        if (!password_verify($code, (string) ($reset['code_hash'] ?? ''))) {
            $this->model->incrementAttempts($email);
            $updated = $this->model->findResetByEmail($email);

            if ($updated !== null && (int) ($updated['attempts'] ?? 0) >= $this->maxAttempts) {
                return ['ok' => false, 'message' => 'Trop de tentatives. Veuillez renvoyer un nouveau code.'];
            }

            return ['ok' => false, 'message' => 'Code invalide ou expire.'];
        }

        $this->model->markVerified($email);

        return ['ok' => true, 'message' => 'Code verifie avec succes.'];
    }

    public function resetPassword(string $email, string $password): array
    {
        $reset = $this->model->findResetByEmail($email);

        if ($reset === null || (int) ($reset['is_verified'] ?? 0) !== 1) {
            return ['ok' => false, 'message' => 'Session de reinitialisation invalide.'];
        }

        if (strtotime((string) $reset['expires_at']) < time()) {
            $this->model->deleteResetRequest($email);
            return ['ok' => false, 'message' => 'Session de reinitialisation expiree.'];
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $updated = $this->model->updatePasswordByEmail($email, $passwordHash);
        $this->model->deleteResetRequest($email);

        if (!$updated) {
            return ['ok' => false, 'message' => 'Impossible de mettre a jour le mot de passe.'];
        }

        return ['ok' => true, 'message' => 'Votre mot de passe a ete reinitialise avec succes.'];
    }

    public function getResetStatus(string $email): ?array
    {
        $reset = $this->model->findResetByEmail($email);
        if ($reset === null) {
            return null;
        }

        $expiresAt = strtotime((string) ($reset['expires_at'] ?? ''));
        return [
            'email' => $email,
            'attempts_left' => max(0, $this->maxAttempts - (int) ($reset['attempts'] ?? 0)),
            'expires_in' => max(0, $expiresAt - time()),
            'resend_wait' => $this->resendWaitSeconds($reset),
            'is_verified' => (int) ($reset['is_verified'] ?? 0) === 1,
        ];
    }

    public function validatePassword(string $password, string $confirmPassword): array
    {
        $errors = [
            'password' => '',
            'confirm_password' => '',
        ];

        if ($password === '') {
            $errors['password'] = 'Le nouveau mot de passe est obligatoire.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/', $password)) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 8 caracteres avec majuscule, minuscule, chiffre et caractere special.';
        }

        if ($confirmPassword === '') {
            $errors['confirm_password'] = 'La confirmation du mot de passe est obligatoire.';
        } elseif ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'La confirmation du mot de passe ne correspond pas.';
        }

        return $errors;
    }

    private function generateCode(): string
    {
        return (string) random_int(100000, 999999);
    }

    private function resendWaitSeconds(array $reset): int
    {
        $lastSentAt = strtotime((string) ($reset['last_sent_at'] ?? 'now'));
        $remaining = ($lastSentAt + $this->resendCooldownSeconds) - time();

        return max(0, $remaining);
    }
}
