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

    private function getCommonStats(?string $startDate = null, ?string $endDate = null): array
    {
        $dashboardStats = $this->statisticsModel->getDashboardStats($startDate, $endDate);

        return [
            'usersStats' => $dashboardStats['users'],
            'entreprisesStats' => $dashboardStats['entreprises'],
            'formateursStats' => $dashboardStats['formateurs'],
            'globalPieStats' => $dashboardStats['globalPie'],
            // Compatibilite avec la carte deja presente dans index.view.php
            'totalUsers' => $dashboardStats['users']['total'],
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

    public function index(): void
    {
        $startDate = $this->sanitizeDate($_GET['start_date'] ?? null);
        $endDate = $this->sanitizeDate($_GET['end_date'] ?? null);
        extract($this->getCommonStats($startDate, $endDate));
        require __DIR__ . '/../Views/statistics.view.php';
    }

    public function dashboardAdmin(): void
    {
        $startDate = $this->sanitizeDate($_GET['start_date'] ?? null);
        $endDate = $this->sanitizeDate($_GET['end_date'] ?? null);
        extract($this->getCommonStats($startDate, $endDate));
        require __DIR__ . '/../Views/index.view.php';
    }

    public function api(): void
    {
        $startDate = $this->sanitizeDate($_GET['start_date'] ?? null);
        $endDate = $this->sanitizeDate($_GET['end_date'] ?? null);

        JsonResponse::send(true, [
            'stats' => $this->getCommonStats($startDate, $endDate),
        ]);
    }

    private function sanitizeDate(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);
        if ($value === '') {
            return null;
        }

        $date = DateTime::createFromFormat('Y-m-d', $value);
        if ($date === false || $date->format('Y-m-d') !== $value) {
            return null;
        }

        return $value;
    }
}
