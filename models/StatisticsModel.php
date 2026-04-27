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
}
