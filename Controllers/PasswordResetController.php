<?php

class PasswordResetController
{
    private PasswordService $service;

    public function __construct(PasswordService $service)
    {
        $this->service = $service;
    }

    public function request(): void
    {
        header('Location: index.php?page=reset-password-request');
        exit;
    }

    public function verifyCode(): void
    {
        header('Location: index.php?page=reset-password-request');
        exit;
    }

    public function resetPassword(): void
    {
        header('Location: index.php?page=reset-password-request');
        exit;
    }

    private function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function cleanEmail(string $email): string
    {
        return trim(mb_strtolower(strip_tags($email), 'UTF-8'));
    }
}
