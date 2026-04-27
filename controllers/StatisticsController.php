<?php

require_once __DIR__ . '/../Models/StatisticsModel.php';
require_once __DIR__ . '/../Models/Database.php';

class StatisticsController
{
    private StatisticsModel $statisticsModel;

    public function __construct()
    {
        $db = new Database();
        $this->statisticsModel = new StatisticsModel($db->connection());
    }

    private function getCommonStats(): array
    {
        $stats = [];
        // Simples stats
        $stats['totalUsers'] = $this->statisticsModel->getTotalUsers();
        $stats['usersToday'] = $this->statisticsModel->getUsersToday();
        $stats['usersThisMonth'] = $this->statisticsModel->getUsersThisMonth();

        $stats['totalEntreprises'] = $this->statisticsModel->getTotalEntreprises();
        $stats['entreprisesThisMonth'] = $this->statisticsModel->getEntreprisesThisMonth();
        $stats['entreprisesActives'] = $this->statisticsModel->getEntreprisesActives();

        $stats['totalFormateurs'] = $this->statisticsModel->getTotalFormateurs();
        $stats['formateursThisMonth'] = $this->statisticsModel->getFormateursThisMonth();
        $stats['avgInscriptionsPerFormateur'] = $this->statisticsModel->getAvgInscriptionsPerFormateur();

        $stats['totalInscriptions'] = $this->statisticsModel->getTotalInscriptions();
        $stats['inscriptionsToday'] = $this->statisticsModel->getInscriptionsToday();
        $stats['inscriptionsThisMonth'] = $this->statisticsModel->getInscriptionsThisMonth();

        $monthsArr = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

        // 1. Line Chart: Utilisateurs par mois
        $usersByMonth = $this->statisticsModel->getUsersByMonth();
        $usersChartLabels = [];
        $usersChartData = [];
        foreach ($usersByMonth as $row) {
            $usersChartLabels[] = $monthsArr[$row['mois'] - 1] . ' ' . $row['annee'];
            $usersChartData[] = $row['total'];
        }
        $stats['usersChartLabels'] = $usersChartLabels;
        $stats['usersChartData'] = $usersChartData;

        // 2. Bar Chart: Inscriptions par mois
        $inscriptionsByMonth = $this->statisticsModel->getAllInscriptionsByMonth();
        $inscriptionsChartLabels = [];
        $inscriptionsChartData = [];
        foreach ($inscriptionsByMonth as $row) {
            $inscriptionsChartLabels[] = $monthsArr[$row['mois'] - 1] . ' ' . $row['annee'];
            $inscriptionsChartData[] = $row['total'];
        }
        $stats['inscriptionsChartLabels'] = $inscriptionsChartLabels;
        $stats['inscriptionsChartData'] = $inscriptionsChartData;

        // 3. Pie Chart: Repartition
        $repartition = $this->statisticsModel->getInscriptionsRepartition();
        $repartitionChartLabels = [];
        $repartitionChartData = [];
        foreach ($repartition as $row) {
            $repartitionChartLabels[] = $row['label'];
            $repartitionChartData[] = $row['total'];
        }
        $stats['repartitionChartLabels'] = $repartitionChartLabels;
        $stats['repartitionChartData'] = $repartitionChartData;

        // Repartition en texte
        $stats['repartitionEntreprise'] = $repartitionChartData[0] ?? 0;
        $stats['repartitionFormateur'] = $repartitionChartData[1] ?? 0;

        return $stats;
    }

    public function index(): void
    {
        extract($this->getCommonStats());
        require __DIR__ . '/../Views/statistics.view.php';
    }

    public function dashboardAdmin(): void
    {
        extract($this->getCommonStats());
        require __DIR__ . '/../Views/index.view.php';
    }
}
