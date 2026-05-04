<?php
require_once __DIR__ . '/Metier.php';

class MetierManager {
    private PDO $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    public function findAll(): array {
        $sql = "SELECT job.*, category.name as category_name 
                FROM job 
                LEFT JOIN category ON job.category_id = category.id 
                ORDER BY job.id DESC";
        $stmt = $this->pdo->query($sql);
        $metiers = [];
        while ($row = $stmt->fetch()) {
            $metiers[] = new Metier($row);
        }
        return $metiers;
    }
    
    public function findById(int $id): ?Metier {
        $sql = "SELECT job.*, category.name as category_name 
                FROM job 
                LEFT JOIN category ON job.category_id = category.id 
                WHERE job.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? new Metier($row) : null;
    }
    
    public function create(Metier $metier): bool {
        $sql = "INSERT INTO job (title, description, competences, specialites, salaire, category_id, session_id) 
                VALUES (:title, :description, :competences, :specialites, :salaire, :category_id, :session_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title' => $metier->getTitle(),
            ':description' => $metier->getDescription(),
            ':competences' => $metier->getCompetences(),
            ':specialites' => $metier->getSpecialites(),
            ':salaire' => $metier->getSalaire(),
            ':category_id' => $metier->getCategoryId(),
            ':session_id' => $metier->getSessionId()
        ]);
    }
    
    public function update(Metier $metier, bool $isAdmin = false): bool {
        if ($isAdmin) {
            $sql = "UPDATE job SET 
                        title = :title, 
                        description = :description, 
                        competences = :competences,
                        specialites = :specialites,
                        salaire = :salaire, 
                        category_id = :category_id 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':id' => $metier->getId(),
                ':title' => $metier->getTitle(),
                ':description' => $metier->getDescription(),
                ':competences' => $metier->getCompetences(),
                ':specialites' => $metier->getSpecialites(),
                ':salaire' => $metier->getSalaire(),
                ':category_id' => $metier->getCategoryId()
            ]);
        } else {
            $sql = "UPDATE job SET 
                        title = :title, 
                        description = :description, 
                        competences = :competences,
                        specialites = :specialites,
                        salaire = :salaire, 
                        category_id = :category_id 
                    WHERE id = :id AND session_id = :session_id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':id' => $metier->getId(),
                ':title' => $metier->getTitle(),
                ':description' => $metier->getDescription(),
                ':competences' => $metier->getCompetences(),
                ':specialites' => $metier->getSpecialites(),
                ':salaire' => $metier->getSalaire(),
                ':category_id' => $metier->getCategoryId(),
                ':session_id' => session_id()
            ]);
        }
    }
    
    public function delete(int $id, bool $isAdmin = false): bool {
        if ($isAdmin) {
            $sql = "DELETE FROM job WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } else {
            $sql = "DELETE FROM job WHERE id = :id AND session_id = :session_id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id, ':session_id' => session_id()]);
        }
    }
    
    public function search(string $keyword): array {
        $searchTerm = '%' . $keyword . '%';
        $sql = "SELECT job.*, category.name as category_name 
                FROM job 
                LEFT JOIN category ON job.category_id = category.id 
                WHERE job.title LIKE ? 
                   OR job.description LIKE ?
                   OR job.competences LIKE ?
                   OR job.specialites LIKE ?
                   OR category.name LIKE ?
                ORDER BY job.id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        $metiers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $metiers[] = new Metier($row);
        }
        return $metiers;
    }
    
    // ========== MÉTHODE POUR INCRÉMENTER LES VUES ==========
    public function incrementerVues(int $id): void {
        $sql = "UPDATE job SET views = views + 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
    
    public function incrementViews(int $id): void {
        $sql = "UPDATE job SET views = views + 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
    
    public function getPopularJobs(int $limit = 5): array {
        $sql = "SELECT job.*, category.name as category_name 
                FROM job 
                LEFT JOIN category ON job.category_id = category.id 
                WHERE views > 0
                ORDER BY views DESC 
                LIMIT $limit";
        $stmt = $this->pdo->query($sql);
        $metiers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $metiers[] = new Metier($row);
        }
        return $metiers;
    }
}
?>