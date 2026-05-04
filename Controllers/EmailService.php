<?php

class EmailService
{
    private Mailer $mailer;
    private int $maxAttempts;
    private int $codeTTLSeconds;
    private int $resendCooldownSeconds;

    public function __construct(
        PDO $conn, // Conservé pour la compatibilité avec index.php
        Mailer $mailer,
        int $maxAttempts = 3,
        int $codeTTLSeconds = 600,
        int $resendCooldownSeconds = 60
    ) {
        $this->mailer = $mailer;
        $this->maxAttempts = $maxAttempts;
        $this->codeTTLSeconds = $codeTTLSeconds;
        $this->resendCooldownSeconds = $resendCooldownSeconds;
    }

    private function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function startRegistrationSession(string $role, string $email, array $data): void
    {
        $this->ensureSessionStarted();
        
        $code = $this->generateCode();
        
        $_SESSION['pending_registration'] = [
            'role' => $role,
            'email' => $email,
            'data' => $data,
            'code' => $code,
            'expires_at' => time() + $this->codeTTLSeconds,
            'sent_at' => time(),
            'attempts' => 0
        ];

        $name = $this->extractNameFromData($role, $data, $email);
        
        try {
            $this->mailer->sendVerificationCode($email, $name, $code);
        } catch (Throwable $exception) {
            throw new RuntimeException("Erreur d'envoi d'email (Détail technique) : " . $exception->getMessage());
        }
    }

    public function resendSessionCode(): void
    {
        $this->ensureSessionStarted();
        $session = $_SESSION['pending_registration'] ?? null;
        
        if (!$session) {
            throw new RuntimeException("Aucune inscription en attente. Veuillez recommencer l'inscription.");
        }

        $remaining = $this->resendWaitSeconds();
        if ($remaining > 0) {
            throw new RuntimeException('Veuillez patienter ' . $remaining . 's avant de renvoyer le code.');
        }

        $code = $this->generateCode();
        $_SESSION['pending_registration']['code'] = $code;
        $_SESSION['pending_registration']['expires_at'] = time() + $this->codeTTLSeconds;
        $_SESSION['pending_registration']['sent_at'] = time();
        $_SESSION['pending_registration']['attempts'] = 0;

        $email = $session['email'];
        $role = $session['role'];
        $data = $session['data'];
        $name = $this->extractNameFromData($role, $data, $email);

        try {
            $this->mailer->sendVerificationCode($email, $name, $code);
        } catch (Throwable $exception) {
            throw new RuntimeException("Erreur d'envoi d'email (Détail technique) : " . $exception->getMessage());
        }
    }

    public function verifySessionCode(string $code): array
    {
        $this->ensureSessionStarted();
        $session = $_SESSION['pending_registration'] ?? null;
        
        if (!$session) {
            return $this->errorResult("Aucune inscription en attente. Veuillez recommencer.");
        }

        $attempts = (int) ($session['attempts'] ?? 0);
        if ($attempts >= $this->maxAttempts) {
            return $this->errorResult('Trop de tentatives. Cliquez sur "Renvoyer le code".');
        }

        $expiresAt = (int) ($session['expires_at'] ?? 0);
        if ($expiresAt < time()) {
            return $this->errorResult('Code expire. Cliquez sur "Renvoyer le code".');
        }

        $storedCode = (string) ($session['code'] ?? '');
        if (!hash_equals($storedCode, $code)) {
            $_SESSION['pending_registration']['attempts']++;
            return $this->errorResult('Code incorrect, reessayez.');
        }

        return $this->successResult('Email verifie avec succes.');
    }

    public function getPendingSessionData(): ?array
    {
        $this->ensureSessionStarted();
        return $_SESSION['pending_registration'] ?? null;
    }

    public function clearPendingSession(): void
    {
        $this->ensureSessionStarted();
        unset($_SESSION['pending_registration']);
    }

    public function attemptsLeft(): int
    {
        $this->ensureSessionStarted();
        $session = $_SESSION['pending_registration'] ?? null;
        if (!$session) {
            return 0;
        }
        
        $attempts = (int) ($session['attempts'] ?? 0);
        return max(0, $this->maxAttempts - $attempts);
    }

    public function resendWaitSeconds(): int
    {
        $this->ensureSessionStarted();
        $session = $_SESSION['pending_registration'] ?? null;
        if (!$session) {
            return 0;
        }

        $sentAt = (int) ($session['sent_at'] ?? 0);
        $diff = time() - $sentAt;
        $remaining = $this->resendCooldownSeconds - $diff;

        return max(0, $remaining);
    }

    public function expiresInSeconds(): int
    {
        $this->ensureSessionStarted();
        $session = $_SESSION['pending_registration'] ?? null;
        if (!$session) {
            return 0;
        }

        $expiresAt = (int) ($session['expires_at'] ?? 0);
        return max(0, $expiresAt - time());
    }

    private function generateCode(): string
    {
        return (string) random_int(100000, 999999);
    }

    private function extractNameFromData(string $role, array $data, string $email): string
    {
        if ($role === 'entreprise') {
            return (string) ($data['nom_entreprise'] ?? $email);
        }

        $prenom = trim((string) ($data['prenom'] ?? ''));
        $nom = trim((string) ($data['nom'] ?? ''));
        $name = trim($prenom . ' ' . $nom);

        return $name !== '' ? $name : $email;
    }

    private function successResult(string $message): array
    {
        return ['ok' => true, 'message' => $message];
    }

    private function errorResult(string $message): array
    {
        return ['ok' => false, 'message' => $message];
    }
}
