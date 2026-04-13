<?php

class DashboardController
{
    private AuthRepository $repository;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getViewData(): array
    {
        $this->ensureAuthenticated();

        return [
            'totalUtilisateurs' => $this->repository->countAll('utilisateur'),
            'totalFormateurs' => $this->repository->countAll('formateur'),
            'totalEntreprises' => $this->repository->countAll('entreprise'),
            'nom' => $_SESSION['user_name'] ?? 'Utilisateur',
            'role' => $_SESSION['role'],
        ];
    }

    public function index(): void
    {
        $dashboardData = $this->getViewData();
        $this->renderDashboardView($dashboardData);
    }

    private function ensureAuthenticated(): void
    {
        if (!isset($_SESSION['role'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    private function renderDashboardView(array $dashboardData): void
    {
        $totalUtilisateurs = $dashboardData['totalUtilisateurs'];
        $totalFormateurs = $dashboardData['totalFormateurs'];
        $totalEntreprises = $dashboardData['totalEntreprises'];
        $nom = $dashboardData['nom'];
        $role = $dashboardData['role'];

        require_once __DIR__ . '/../views/dashboard.view.php';
    }
}
