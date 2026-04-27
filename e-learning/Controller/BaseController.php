<?php

declare(strict_types=1);

namespace App\Controller;

class BaseController
{
    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $newViewPath = __DIR__ . '/../View/' . $view . '.php';
        if (is_file($newViewPath)) {
            require $newViewPath;
            return;
        }

        http_response_code(500);
        echo 'Vue introuvable.';
    }

    protected function redirect(string $route, array $params = []): void
    {
        $query = ['r' => $route] + $params;
        header('Location: index.php?' . http_build_query($query), true, 302);
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

    protected function setOldInput(array $data): void
    {
        $_SESSION['old_input'] = $data;
    }

    protected function getOldInput(): array
    {
        if (!isset($_SESSION['old_input']) || !is_array($_SESSION['old_input'])) {
            return [];
        }

        $data = $_SESSION['old_input'];
        unset($_SESSION['old_input']);

        return $data;
    }
}

