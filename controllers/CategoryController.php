<?php
require_once __DIR__ . '/../models/CategoryManager.php';

class CategoryController {
    private CategoryManager $categoryManager;
    
    public function __construct(PDO $pdo) {
        $this->categoryManager = new CategoryManager($pdo);
    }
    
    // Afficher la liste des catégories (BackOffice)
    public function list(): void {
        $categories = $this->categoryManager->findAll();
        require_once __DIR__ . '/../views/back/categories/list.php';
    }
    
    // Récupérer toutes les catégories (utilisé dans FrontOffice)
    public function getAllCategories() {
        return $this->categoryManager->findAll();
    }
    
    // Ajouter une catégorie
    public function add(): void {
        session_start();
        
        $name = trim($_POST['name'] ?? '');
        $errors = [];
        
        if (empty($name)) {
            $errors[] = "Le nom de la catégorie est obligatoire";
        } elseif (strlen($name) < 3) {
            $errors[] = "Le nom doit contenir au moins 3 caractères";
        } elseif (strlen($name) > 100) {
            $errors[] = "Le nom ne peut pas dépasser 100 caractères";
        }
        
        if (empty($errors)) {
            try {
                $this->categoryManager->create($name);
                $_SESSION['success'] = "Catégorie « " . htmlspecialchars($name) . " » ajoutée";
            } catch (Exception $e) {
                $_SESSION['errors'] = [$e->getMessage()];
            }
        } else {
            $_SESSION['errors'] = $errors;
        }
        
        header('Location: index.php?action=admin_categories');
        exit;
    }
    
    // Supprimer une catégorie
    public function delete(int $id): void {
        session_start();
        try {
            $this->categoryManager->delete($id);
            $_SESSION['success'] = "Catégorie supprimée avec succès";
        } catch (Exception $e) {
            $_SESSION['errors'] = [$e->getMessage()];
        }
        header('Location: index.php?action=admin_categories');
        exit;
    }
}
?>