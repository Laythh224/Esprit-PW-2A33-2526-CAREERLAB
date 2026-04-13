<?php

class DashboardController
{
    private UserModel $userModel;
    private FormateurModel $formateurModel;
    private EntrepriseModel $entrepriseModel;

    public function __construct(UserModel $userModel, FormateurModel $formateurModel, EntrepriseModel $entrepriseModel)
    {
        $this->userModel = $userModel;
        $this->formateurModel = $formateurModel;
        $this->entrepriseModel = $entrepriseModel;
    }

    public function getViewData(): array
    {
        $this->ensureAuthenticated();

        return [
            'totalUtilisateurs' => $this->userModel->count(),
            'totalFormateurs' => $this->formateurModel->count(),
            'totalEntreprises' => $this->entrepriseModel->count(),
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
