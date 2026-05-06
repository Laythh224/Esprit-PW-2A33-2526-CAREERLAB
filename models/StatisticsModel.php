<?php

class StatisticsModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // --- Utilisateurs ---
    public function getTotalUsers(): int { 
        return (int) $this->conn->query('SELECT COUNT(*) FROM utilisateur')->fetchColumn(); 
    }
    public function getUsersToday(): int { 
        return (int) $this->conn->query('SELECT COUNT(*) FROM utilisateur WHERE DATE(created_at) = CURDATE()')->fetchColumn(); 
    }
    public function getUsersThisMonth(): int { 
        return (int) $this->conn->query('SELECT COUNT(*) FROM utilisateur WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())')->fetchColumn(); 
    }
    public function getUsersByMonth(): array {
        $sql = "SELECT MONTH(created_at) as mois, YEAR(created_at) as annee, COUNT(*) as total 
                FROM utilisateur 
                GROUP BY YEAR(created_at), MONTH(created_at) 
                ORDER BY annee ASC, mois ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    // --- Entreprises ---
    public function getTotalEntreprises(): int { 
        return (int) $this->conn->query('SELECT COUNT(*) FROM entreprise')->fetchColumn(); 
    }
    public function getEntreprisesThisMonth(): int { 
        return (int) $this->conn->query('SELECT COUNT(*) FROM entreprise WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())')->fetchColumn(); 
    }
    public function getEntreprisesActives(): int { 
        // Entreprises qui ont au moins une inscription
        return (int) $this->conn->query('SELECT COUNT(DISTINCT id_entreprise) FROM inscription_entreprise')->fetchColumn(); 
    }

    // --- Formateurs ---
    public function getTotalFormateurs(): int { 
        return (int) $this->conn->query('SELECT COUNT(*) FROM formateur')->fetchColumn(); 
    }
    public function getFormateursThisMonth(): int { 
        return (int) $this->conn->query('SELECT COUNT(*) FROM formateur WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())')->fetchColumn(); 
    }
    public function getAvgInscriptionsPerFormateur(): float {
        $sql = "SELECT AVG(inscriptions_count) FROM (
                    SELECT COUNT(id_user) as inscriptions_count FROM inscription_formateur GROUP BY id_formateur
                ) as subquery";
        $avg = $this->conn->query($sql)->fetchColumn();
        return $avg ? (float) $avg : 0.0;
    }

    // --- Inscriptions ---
    public function getTotalInscriptions(): int {
        return (int) $this->conn->query('SELECT (SELECT COUNT(*) FROM inscription_entreprise) + (SELECT COUNT(*) FROM inscription_formateur)')->fetchColumn();
    }
    public function getInscriptionsToday(): int {
        return (int) $this->conn->query('SELECT 
            (SELECT COUNT(*) FROM inscription_entreprise WHERE DATE(created_at) = CURDATE()) + 
            (SELECT COUNT(*) FROM inscription_formateur WHERE DATE(created_at) = CURDATE())')->fetchColumn();
    }
    public function getInscriptionsThisMonth(): int {
        return (int) $this->conn->query('SELECT 
            (SELECT COUNT(*) FROM inscription_entreprise WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) + 
            (SELECT COUNT(*) FROM inscription_formateur WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()))')->fetchColumn();
    }
    public function getInscriptionsRepartition(): array {
        $sql = "SELECT 'Entreprises' as label, COUNT(*) as total FROM inscription_entreprise 
                UNION ALL 
                SELECT 'Formateurs' as label, COUNT(*) as total FROM inscription_formateur";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
    public function getAllInscriptionsByMonth(): array {
        $sql = "SELECT annee, mois, SUM(total) as total FROM (
                    SELECT YEAR(created_at) as annee, MONTH(created_at) as mois, COUNT(*) as total FROM inscription_entreprise GROUP BY annee, mois
                    UNION ALL
                    SELECT YEAR(created_at) as annee, MONTH(created_at) as mois, COUNT(*) as total FROM inscription_formateur GROUP BY annee, mois
                ) as combined 
                GROUP BY annee, mois 
                ORDER BY annee ASC, mois ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getAccountsByMonth(): array
    {
        $sql = "SELECT annee, mois, SUM(total) as total FROM (
                    SELECT YEAR(created_at) as annee, MONTH(created_at) as mois, COUNT(*) as total FROM utilisateur GROUP BY annee, mois
                    UNION ALL
                    SELECT YEAR(created_at) as annee, MONTH(created_at) as mois, COUNT(*) as total FROM entreprise GROUP BY annee, mois
                    UNION ALL
                    SELECT YEAR(created_at) as annee, MONTH(created_at) as mois, COUNT(*) as total FROM formateur GROUP BY annee, mois
                ) as combined
                GROUP BY annee, mois
                ORDER BY annee ASC, mois ASC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getDashboardStats(?string $startDate = null, ?string $endDate = null): array
    {
        return [
            'users' => $this->getEntityStats('utilisateur', $startDate, $endDate),
            'entreprises' => $this->getEntityStats('entreprise', $startDate, $endDate),
            'formateurs' => $this->getEntityStats('formateur', $startDate, $endDate),
            'globalPie' => $this->getGlobalRepartition($startDate, $endDate),
        ];
    }

    private function getEntityStats(string $table, ?string $startDate, ?string $endDate): array
    {
        $where = $this->buildDateWhereClause($startDate, $endDate);

        $totalSql = "SELECT COUNT(*) FROM {$table} {$where}";
        $stmtTotal = $this->conn->prepare($totalSql);
        $this->bindDateParams($stmtTotal, $startDate, $endDate);
        $stmtTotal->execute();
        $total = (int) ($stmtTotal->fetchColumn() ?: 0);

        $todaySql = "SELECT COUNT(*) FROM {$table} WHERE DATE(created_at) = CURDATE()";
        if ($where !== '') {
            $todaySql .= " AND " . substr($where, 6);
        }
        $stmtToday = $this->conn->prepare($todaySql);
        $this->bindDateParams($stmtToday, $startDate, $endDate);
        $stmtToday->execute();
        $today = (int) ($stmtToday->fetchColumn() ?: 0);

        $monthSql = "SELECT COUNT(*) FROM {$table} WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
        if ($where !== '') {
            $monthSql .= " AND " . substr($where, 6);
        }
        $stmtMonth = $this->conn->prepare($monthSql);
        $this->bindDateParams($stmtMonth, $startDate, $endDate);
        $stmtMonth->execute();
        $thisMonth = (int) ($stmtMonth->fetchColumn() ?: 0);

        $evolution = $this->getDailyEvolution($table, $startDate, $endDate);

        return [
            'total' => $total,
            'today' => $today,
            'thisMonth' => $thisMonth,
            'labels' => $evolution['labels'],
            'data' => $evolution['data'],
        ];
    }

    private function getDailyEvolution(string $table, ?string $startDate, ?string $endDate): array
    {
        $where = $this->buildDateWhereClause($startDate, $endDate);
        $sql = "SELECT DATE(created_at) AS jour, COUNT(*) AS total
                FROM {$table}
                {$where}
                GROUP BY DATE(created_at)
                ORDER BY jour ASC";

        $stmt = $this->conn->prepare($sql);
        $this->bindDateParams($stmt, $startDate, $endDate);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $labels = [];
        $data = [];
        foreach ($rows as $row) {
            $labels[] = (string) $row['jour'];
            $data[] = (int) $row['total'];
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    private function getGlobalRepartition(?string $startDate, ?string $endDate): array
    {
        $users = $this->countWithDateFilter('utilisateur', $startDate, $endDate);
        $entreprises = $this->countWithDateFilter('entreprise', $startDate, $endDate);
        $formateurs = $this->countWithDateFilter('formateur', $startDate, $endDate);

        return [
            'labels' => ['Utilisateurs', 'Entreprises', 'Formateurs'],
            'data' => [$users, $entreprises, $formateurs],
        ];
    }

    private function countWithDateFilter(string $table, ?string $startDate, ?string $endDate): int
    {
        $where = $this->buildDateWhereClause($startDate, $endDate);
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$table} {$where}");
        $this->bindDateParams($stmt, $startDate, $endDate);
        $stmt->execute();

        return (int) ($stmt->fetchColumn() ?: 0);
    }

    private function buildDateWhereClause(?string $startDate, ?string $endDate): string
    {
        if ($startDate !== null && $endDate !== null) {
            return "WHERE DATE(created_at) BETWEEN :startDate AND :endDate";
        }

        if ($startDate !== null) {
            return "WHERE DATE(created_at) >= :startDate";
        }

        if ($endDate !== null) {
            return "WHERE DATE(created_at) <= :endDate";
        }

        return '';
    }

    private function bindDateParams(PDOStatement $stmt, ?string $startDate, ?string $endDate): void
    {
        if ($startDate !== null) {
            $stmt->bindValue(':startDate', $startDate);
        }
        if ($endDate !== null) {
            $stmt->bindValue(':endDate', $endDate);
        }
    }
}
