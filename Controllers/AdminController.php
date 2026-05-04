<?php

class AdminController
{
    /** @var array<string, string> */
    private array $viewAliases = [
        'dashboard' => 'dashboard-admin',
        'animals' => 'gestion-utilisateurs',
        'records' => 'gestion-formateurs',
        'users' => 'gestion-utilisateurs',
        'utilisateurs' => 'gestion-utilisateurs',
        'formateurs' => 'gestion-formateurs',
        'entreprises' => 'gestion-entreprises',
        'companies' => 'gestion-entreprises',
        'demandes' => 'demandes-ia',
        'ai' => 'demandes-ia',
        'support' => 'demandes-ia',
    ];

    public function resolveAdminPage(?string $view, string $fallback = 'dashboard-admin'): string
    {
        if ($view === null) {
            return $fallback;
        }

        $viewKey = strtolower(trim($view));
        if ($viewKey === '') {
            return $fallback;
        }

        return $this->viewAliases[$viewKey] ?? $fallback;
    }
}

