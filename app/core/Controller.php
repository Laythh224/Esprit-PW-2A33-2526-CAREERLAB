<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    /**
     * @param array<string, mixed> $data
     */
    protected function render(string $view, array $data = []): void
    {
        $viewFile = __DIR__ . '/../views/pages/' . $view . '.php';
        $layoutFile = __DIR__ . '/../views/layouts/main.php';

        if (!is_file($viewFile)) {
            throw new \RuntimeException('View not found: ' . $viewFile);
        }

        extract($data, EXTR_SKIP);
        require $layoutFile;
    }

    protected function redirect(string $route): void
    {
        header('Location: index.php?route=' . ltrim($route, '/'));
        exit;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function renderStandalone(string $viewPath, array $data = []): void
    {
        $viewFile = __DIR__ . '/../views/' . ltrim($viewPath, '/') . '.php';

        if (!is_file($viewFile)) {
            throw new \RuntimeException('View not found: ' . $viewFile);
        }

        extract($data, EXTR_SKIP);
        require $viewFile;
    }
}
