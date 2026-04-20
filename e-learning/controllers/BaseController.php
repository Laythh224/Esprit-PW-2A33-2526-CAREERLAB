<?php

namespace App\controllers;

class BaseController
{
    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../viewss/' . $view . '.php';
    }

    protected function redirect(string $route): void
    {
        header('Location: index.php?r=' . urlencode($route));
        exit;
    }

    protected function setFlash(string $message): void
    {
        $_SESSION['flash_message'] = $message;
    }

    protected function getFlash(): ?string
    {
        if (!isset($_SESSION['flash_message'])) {
            return null;
        }

        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);

        return $message;
    }
}

