<?php

class TokenService
{
    private TokenResetModel $model;
    private Mailer $mailer;
    private string $baseUrl;
    private int $tokenTtlMinutes;
    private int $maxRequestsPerHour;

    public function __construct(
        TokenResetModel $model,
        Mailer $mailer,
        string $baseUrl = 'http://localhost/mon_site',
        int $tokenTtlMinutes = 15,
        int $maxRequestsPerHour = 5
    ) {
        $this->model = $model;
        $this->mailer = $mailer;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->tokenTtlMinutes = $tokenTtlMinutes;
        $this->maxRequestsPerHour = $maxRequestsPerHour;
    }

    /**
     * Demander une réinitialisation de mot de passe
     * Retourne TOUJOURS le même message (sécurité)
     */
    public function requestReset(string $email): array
    {
        // Nettoyer les tokens expirés
        $this->model->purgeExpiredTokens();

        $genericMessage = 'Si un compte existe avec cette adresse email, un lien de réinitialisation a été envoyé.';

        // Normaliser l'email
        $email = strtolower(trim($email));

        // Vérifier si le compte existe
        $account = $this->model->findAccountByEmail($email);
        if ($account === null) {
            // NE PAS révéler que le compte n'existe pas (sécurité)
            return [
                'ok' => true,
                'message' => $genericMessage,
            ];
        }

        // Vérifier le rate limiting
        if (!$this->model->checkRateLimit($email, $this->maxRequestsPerHour)) {
            // Toujours retourner le message générique même si bloqué
            return [
                'ok' => true,
                'message' => $genericMessage,
            ];
        }

        try {
            // Générer un token sécurisé
            $token = $this->generateSecureToken();
            $expiresAt = date('Y-m-d H:i:s', time() + ($this->tokenTtlMinutes * 60));

            // Créer le token en base
            if (!$this->model->createToken($email, $token, $expiresAt)) {
                throw new RuntimeException('Impossible de créer le token de réinitialisation.');
            }

            // Construire le lien
            $resetLink = sprintf(
                '%s/index.php?page=reset-password&token=%s',
                $this->baseUrl,
                urlencode($token)
            );

            // Envoyer l'email
            $this->mailer->sendPasswordResetLink(
                $email,
                $account['display_name'],
                $resetLink,
                $this->tokenTtlMinutes
            );

            return [
                'ok' => true,
                'message' => $genericMessage,
            ];
        } catch (Throwable $exception) {
            // Log l'erreur mais retourne le message générique
            error_log('Password Reset Error: ' . $exception->getMessage());
            return [
                'ok' => true,
                'message' => $genericMessage,
            ];
        }
    }

    /**
     * Valider un token
     */
    public function validateToken(string $token): array
    {
        // Nettoyer les tokens expirés
        $this->model->purgeExpiredTokens();

        // Vérifier si le token existe et est valide
        $tokenData = $this->model->findValidToken($token);

        if ($tokenData === null) {
            return [
                'ok' => false,
                'message' => 'Le lien de réinitialisation est invalide ou expiré.',
                'token' => null,
                'email' => null,
            ];
        }

        return [
            'ok' => true,
            'message' => 'Token valide.',
            'token' => $token,
            'email' => (string) $tokenData['email'],
        ];
    }

    /**
     * Obtenir les détails du token
     */
    public function getTokenDetails(string $token): ?array
    {
        return $this->model->getTokenInfo($token);
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(string $token, string $email, string $password, string $confirmPassword): array
    {
        // Valider les mots de passe
        $validation = $this->validatePasswords($password, $confirmPassword);
        if (!$validation['ok']) {
            return $validation;
        }

        // Valider le token
        $tokenValidation = $this->validateToken($token);
        if (!$tokenValidation['ok']) {
            return [
                'ok' => false,
                'message' => 'Le lien de réinitialisation est invalide ou expiré.',
            ];
        }

        // Vérifier que l'email correspond
        if ($tokenValidation['email'] !== $email) {
            return [
                'ok' => false,
                'message' => 'Les informations ne correspondent pas.',
            ];
        }

        try {
            // Hasher le mot de passe
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Mettre à jour le mot de passe
            if (!$this->model->updatePasswordByEmail($email, $passwordHash)) {
                return [
                    'ok' => false,
                    'message' => 'Impossible de mettre à jour le mot de passe.',
                ];
            }

            // Marquer le token comme utilisé
            $this->model->markTokenAsUsed($token);

            // Envoyer email de confirmation (optionnel mais recommandé)
            try {
                $account = $this->model->findAccountByEmail($email);
                if ($account !== null) {
                    $this->mailer->sendPasswordChangedNotification($email, $account['display_name']);
                }
            } catch (Throwable $e) {
                // Log mais ne pas bloquer le processus
                error_log('Failed to send confirmation email: ' . $e->getMessage());
            }

            return [
                'ok' => true,
                'message' => 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.',
            ];
        } catch (Throwable $exception) {
            error_log('Password Reset Failed: ' . $exception->getMessage());
            return [
                'ok' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer.',
            ];
        }
    }

    /**
     * Valider les mots de passe
     */
    public function validatePasswords(string $password, string $confirmPassword): array
    {
        $errors = [];

        if (empty($password)) {
            $errors['password'] = 'Le mot de passe est obligatoire.';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères.';
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $errors['password'] = 'Le mot de passe doit contenir au moins une majuscule.';
        } elseif (!preg_match('/[a-z]/', $password)) {
            $errors['password'] = 'Le mot de passe doit contenir au moins une minuscule.';
        } elseif (!preg_match('/[0-9]/', $password)) {
            $errors['password'] = 'Le mot de passe doit contenir au moins un chiffre.';
        } elseif (!preg_match('/[!@#$%^&*()_\-+=\[\]{};:\'",.<>?\/\\|`~]/', $password)) {
            $errors['password'] = 'Le mot de passe doit contenir au moins un caractère spécial.';
        }

        if (empty($confirmPassword)) {
            $errors['confirm_password'] = 'Veuillez confirmer votre mot de passe.';
        } elseif ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Les mots de passe ne correspondent pas.';
        }

        return [
            'ok' => count($errors) === 0,
            'errors' => $errors,
        ];
    }

    /**
     * Générer un token sécurisé
     */
    private function generateSecureToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }
}
