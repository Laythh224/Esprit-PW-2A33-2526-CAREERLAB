<?php
require_once __DIR__ . '/Category.php';

class CategoryManager {
    private PDO $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    public function findAll(): array {
        $sql = "SELECT * FROM category ORDER BY name ASC";
        $stmt = $this->pdo->query($sql);
        $categories = [];
        while ($row = $stmt->fetch()) {
            $categories[] = new Category($row);
        }
        return $categories;
    }
    
    public function findById(int $id): ?Category {
        $sql = "SELECT * FROM category WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? new Category($row) : null;
    }
    
    public function create(string $name): bool {
        $name = trim($name);
        if (empty($name)) {
            throw new Exception("Le nom de la catégorie est obligatoire");
        }
        
        $sql = "INSERT INTO category (name) VALUES (:name)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':name' => $name]);
    }
    
    public function delete(int $id): bool {
        // Vérifier si des métiers sont liés
        $check = $this->pdo->prepare("SELECT COUNT(*) FROM job WHERE category_id = :id");
        $check->execute([':id' => $id]);
        if ($check->fetchColumn() > 0) {
            throw new Exception("Impossible de supprimer : cette catégorie contient des métiers");
        }
        
        $sql = "DELETE FROM category WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>