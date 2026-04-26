<?php

class MainController
{
    private HomeController $homeController;
    /** @var array<string, string> */
    private array $homeRoutes;
    /** @var array<string, array{0:object, 1:string}> */
    private array $controllerRoutes;
    /** @var array<string, string> */
    private array $viewRoutes;

    /**
     * @param array<string, string> $homeRoutes
     * @param array<string, array{0:object, 1:string}> $controllerRoutes
     * @param array<string, string> $viewRoutes
     */
    public function __construct(
        HomeController $homeController,
        array $homeRoutes,
        array $controllerRoutes,
        array $viewRoutes
    ) {
        $this->homeController = $homeController;
        $this->homeRoutes = $homeRoutes;
        $this->controllerRoutes = $controllerRoutes;
        $this->viewRoutes = $viewRoutes;
    }

    public function handle(string $page): bool
    {
        if (isset($this->homeRoutes[$page])) {
            $action = $this->homeRoutes[$page];
            $this->homeController->$action();
            return true;
        }

        if (isset($this->controllerRoutes[$page])) {
            $this->ensureAdminAccessIfNeeded($page);
            [$controller, $action] = $this->controllerRoutes[$page];
            $controller->$action();
            return true;
        }

        if (isset($this->viewRoutes[$page])) {
            $this->ensureAdminAccessIfNeeded($page);
            require_once $this->viewRoutes[$page];
            return true;
        }

        return false;
    }

    private function ensureAdminAccessIfNeeded(string $page): void
    {
        $adminPages = [
            'admin',
            'dashboard-admin',
            'gestion-utilisateurs',
            'gestion-formateurs',
            'gestion-entreprises',
            'inscription-entreprise',
            'inscription-formateur',
        ];

        if (!in_array($page, $adminPages, true)) {
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
    }
}

