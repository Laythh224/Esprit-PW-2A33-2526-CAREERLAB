<?php
// models/MetierManager.php - Gestionnaire des métiers (Modèle)

require_once '../config.php';

class MetierManager {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    // CREATE - Ajouter un nouveau métier
    public function ajouterMetier($nom, $description, $salaire, $secteur) {
        try {
            $sql = "INSERT INTO metiers (nom, description, salaire_moyen, secteur) VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nom, $description, $salaire, $secteur]);
        } catch (PDOException $e) {
            error_log("Erreur ajout métier : " . $e->getMessage());
            return false;
        }
    }

    // READ - Récupérer tous les métiers
    public function getAllMetiers() {
        try {
            $sql = "SELECT * FROM metiers ORDER BY date_creation DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur récupération métiers : " . $e->getMessage());
            return [];
        }
    }

    // READ - Récupérer un métier par ID
    public function getMetierById($id) {
        try {
            $sql = "SELECT * FROM metiers WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur récupération métier : " . $e->getMessage());
            return false;
        }
    }

    // UPDATE - Modifier un métier
    public function modifierMetier($id, $nom, $description, $salaire, $secteur) {
        try {
            $sql = "UPDATE metiers SET nom = ?, description = ?, salaire_moyen = ?, secteur = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nom, $description, $salaire, $secteur, $id]);
        } catch (PDOException $e) {
            error_log("Erreur modification métier : " . $e->getMessage());
            return false;
        }
    }

    // DELETE - Supprimer un métier
    public function supprimerMetier($id) {
        try {
            $sql = "DELETE FROM metiers WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur suppression métier : " . $e->getMessage());
            return false;
        }
    }

    // SEARCH - Rechercher des métiers
    public function rechercherMetiers($searchTerm) {
        try {
            if (empty(trim($searchTerm))) {
                return $this->getAllMetiers();
            }

            $sql = "SELECT * FROM metiers WHERE 
                    CAST(id AS CHAR) LIKE ? OR 
                    nom LIKE ? OR 
                    description LIKE ? OR 
                    secteur LIKE ? OR
                    CAST(salaire_moyen AS CHAR) LIKE ?";
            $stmt = $this->pdo->prepare($sql);
            $searchPattern = "%$searchTerm%";
            $stmt->execute([$searchPattern, $searchPattern, $searchPattern, $searchPattern, $searchPattern]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur recherche métiers : " . $e->getMessage());
            return [];
        }
    }

    // STATISTICS - Statistiques complètes des métiers
    public function getStatistiques() {
        try {
            $stats = [];

            // Nombre total de métiers
            $stmt = $this->pdo->query("SELECT COUNT(*) as total_metiers FROM metiers");
            $stats['total_metiers'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_metiers'];

            // Nombre de secteurs représentés
            $stmt = $this->pdo->query("SELECT COUNT(DISTINCT secteur) as secteurs_count FROM metiers WHERE secteur IS NOT NULL AND secteur != ''");
            $stats['secteurs_count'] = $stmt->fetch(PDO::FETCH_ASSOC)['secteurs_count'];

            // Répartition par secteur
            $stmt = $this->pdo->query("SELECT COALESCE(secteur, 'Non défini') as secteur, COUNT(*) as count FROM metiers GROUP BY secteur");
            $secteurs = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $secteurs[$row['secteur']] = $row['count'];
            }
            $stats['secteurs'] = $secteurs;

            // Statistiques salariales
            $stmt = $this->pdo->query("SELECT salaire_moyen FROM metiers WHERE salaire_moyen IS NOT NULL ORDER BY salaire_moyen");
            $salaires = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($salaires)) {
                $stats['salaire_min'] = min($salaires);
                $stats['salaire_max'] = max($salaires);
                $stats['salaire_moyen_global'] = array_sum($salaires) / count($salaires);
                $stats['salaires_detail'] = $salaires;
            } else {
                $stats['salaire_min'] = 0;
                $stats['salaire_max'] = 0;
                $stats['salaire_moyen_global'] = 0;
                $stats['salaires_detail'] = [];
            }

            // Métier le mieux payé
            $stmt = $this->pdo->query("SELECT * FROM metiers WHERE salaire_moyen = (SELECT MAX(salaire_moyen) FROM metiers WHERE salaire_moyen IS NOT NULL) LIMIT 1");
            $stats['meilleur_salaire'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Métier le moins payé
            $stmt = $this->pdo->query("SELECT * FROM metiers WHERE salaire_moyen = (SELECT MIN(salaire_moyen) FROM metiers WHERE salaire_moyen IS NOT NULL AND salaire_moyen > 0) LIMIT 1");
            $stats['pire_salaire'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Plus récente date d'ajout
            $stmt = $this->pdo->query("SELECT MAX(date_creation) as plus_recent FROM metiers");
            $stats['plus_recent'] = $stmt->fetch(PDO::FETCH_ASSOC)['plus_recent'];

            // Derniers métiers ajoutés (5 plus récents)
            $stmt = $this->pdo->query("SELECT * FROM metiers ORDER BY date_creation DESC LIMIT 5");
            $stats['derniers_metiers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $stats;
        } catch (PDOException $e) {
            error_log("Erreur statistiques complètes : " . $e->getMessage());
            return [
                'total_metiers' => 0,
                'secteurs_count' => 0,
                'secteurs' => [],
                'salaire_min' => 0,
                'salaire_max' => 0,
                'salaire_moyen_global' => 0,
                'salaires_detail' => [],
                'meilleur_salaire' => null,
                'pire_salaire' => null,
                'plus_recent' => null,
                'derniers_metiers' => []
            ];
        }
    }

    // STATISTICS - Statistiques des métiers (méthode existante pour compatibilité)
    public function getStatistics() {
        try {
            $stats = [];

            // Nombre total de métiers
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM metiers");
            $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Nombre par secteur
            $stmt = $this->pdo->query("SELECT secteur, COUNT(*) as count FROM metiers WHERE secteur IS NOT NULL AND secteur != '' GROUP BY secteur");
            $stats['par_secteur'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Salaire moyen global
            $stmt = $this->pdo->query("SELECT AVG(salaire_moyen) as salaire_moyen FROM metiers WHERE salaire_moyen IS NOT NULL");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['salaire_moyen'] = $result['salaire_moyen'];

            return $stats;
        } catch (PDOException $e) {
            error_log("Erreur statistiques : " . $e->getMessage());
            return ['total' => 0, 'par_secteur' => [], 'salaire_moyen' => 0];
        }
    }
}
?>