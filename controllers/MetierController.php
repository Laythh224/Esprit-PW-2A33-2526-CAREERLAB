<?php
require_once __DIR__ . '/../models/MetierManager.php';
require_once __DIR__ . '/../models/CategoryManager.php';

class MetierController {
    private MetierManager $metierManager;
    private CategoryManager $categoryManager;
    
    public function __construct(PDO $pdo) {
        $this->metierManager = new MetierManager($pdo);
        $this->categoryManager = new CategoryManager($pdo);
    }
    
    // ========== FRONTOFFICE ==========
    public function getAllMetiers() {
        return $this->metierManager->findAll();
    }
    
    // MODIFIÉE - Ajout de l'incrémentation des vues
    public function getMetierById($id) {
        $this->metierManager->incrementViews($id);
        return $this->metierManager->findById($id);
    }
    
    public function searchMetiers($keyword) {
        return $this->metierManager->search($keyword);
    }
    
    public function addPublic(): void {
        session_start();
        $errors = [];
        
        if (empty($_POST['title'])) {
            $errors[] = "Le titre du métier est obligatoire";
        } elseif (strlen(trim($_POST['title'])) < 3) {
            $errors[] = "Le titre doit contenir au moins 3 caractères";
        }
        
        if (!empty($_POST['salaire']) && !is_numeric($_POST['salaire'])) {
            $errors[] = "Le salaire doit être un nombre valide";
        }
        
        if (empty($errors)) {
            try {
                $metier = new Metier([
                    'title' => $_POST['title'],
                    'description' => $_POST['description'] ?? '',
                    'competences' => $_POST['competences'] ?? '',
                    'specialites' => $_POST['specialites'] ?? '',
                    'salaire' => $_POST['salaire'] ?? null,
                    'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
                    'session_id' => session_id()
                ]);
                
                $this->metierManager->create($metier);
                $_SESSION['success'] = "Métier ajouté avec succès !";
            } catch (Exception $e) {
                $_SESSION['errors'] = [$e->getMessage()];
            }
        } else {
            $_SESSION['errors'] = $errors;
        }
        
        header('Location: index.php?action=metiers');
        exit;
    }
    
    public function editPublicForm(int $id): void {
        $metier = $this->metierManager->findById($id);
        $categories = $this->categoryManager->findAll();
        
        if (!$metier) {
            header('Location: index.php?action=metiers');
            exit;
        }
        
        // Vérifier que le métier appartient à l'utilisateur
        if ($metier->getSessionId() !== session_id()) {
            $_SESSION['errors'] = ["Vous n'êtes pas autorisé à modifier ce métier"];
            header('Location: index.php?action=metiers');
            exit;
        }
        
        require_once __DIR__ . '/../views/front/edit_metier.php';
    }
    
    public function editPublic(int $id): void {
        session_start();
        $errors = [];
        
        // Vérifier que le métier appartient à l'utilisateur
        $metier = $this->metierManager->findById($id);
        if ($metier && $metier->getSessionId() !== session_id()) {
            $_SESSION['errors'] = ["Vous n'êtes pas autorisé à modifier ce métier"];
            header('Location: index.php?action=metiers');
            exit;
        }
        
        if (empty($_POST['title'])) {
            $errors[] = "Le titre du métier est obligatoire";
        }
        
        if (empty($errors)) {
            try {
                $metier = new Metier([
                    'id' => $id,
                    'title' => $_POST['title'],
                    'description' => $_POST['description'] ?? '',
                    'competences' => $_POST['competences'] ?? '',
                    'specialites' => $_POST['specialites'] ?? '',
                    'salaire' => $_POST['salaire'] ?? null,
                    'category_id' => $_POST['category_id'] ?? null
                ]);
                
                $this->metierManager->update($metier, false);
                $_SESSION['success'] = "Métier modifié avec succès !";
            } catch (Exception $e) {
                $_SESSION['errors'] = [$e->getMessage()];
            }
        } else {
            $_SESSION['errors'] = $errors;
        }
        
        header('Location: index.php?action=metiers');
        exit;
    }
    
    public function deletePublic(int $id): void {
        session_start();
        
        // Vérifier que le métier appartient à l'utilisateur
        $metier = $this->metierManager->findById($id);
        if ($metier && $metier->getSessionId() !== session_id()) {
            $_SESSION['errors'] = ["Vous n'êtes pas autorisé à supprimer ce métier"];
            header('Location: index.php?action=metiers');
            exit;
        }
        
        $this->metierManager->delete($id, false);
        $_SESSION['success'] = "Métier supprimé avec succès !";
        header('Location: index.php?action=metiers');
        exit;
    }
    
    public function search(): void {
        $keyword = $_GET['search'] ?? '';
        $metiers = $this->metierManager->search($keyword);
        $categories = $this->categoryManager->findAll();
        require_once __DIR__ . '/../views/front/metiers.php';
    }
    
    // ========== BACKOFFICE (ADMIN PEUT TOUT FAIRE) ==========
    public function listBack(): void {
        $metiers = $this->metierManager->findAll();
        $categories = $this->categoryManager->findAll();
        require_once __DIR__ . '/../views/back/metiers/list.php';
    }
    
    public function addForm(): void {
        $categories = $this->categoryManager->findAll();
        require_once __DIR__ . '/../views/back/metiers/add.php';
    }
    
    public function add(): void {
        session_start();
        $errors = [];
        
        if (empty($_POST['title'])) {
            $errors[] = "Le titre du métier est obligatoire";
        } elseif (strlen(trim($_POST['title'])) < 3) {
            $errors[] = "Le titre doit contenir au moins 3 caractères";
        }
        
        if (!empty($_POST['salaire']) && !is_numeric($_POST['salaire'])) {
            $errors[] = "Le salaire doit être un nombre valide";
        }
        
        if (empty($errors)) {
            try {
                $metier = new Metier([
                    'title' => $_POST['title'],
                    'description' => $_POST['description'] ?? '',
                    'competences' => $_POST['competences'] ?? '',
                    'specialites' => $_POST['specialites'] ?? '',
                    'salaire' => $_POST['salaire'] ?? null,
                    'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
                    'session_id' => session_id()
                ]);
                
                $this->metierManager->create($metier);
                $_SESSION['success'] = "Métier ajouté avec succès";
            } catch (Exception $e) {
                $_SESSION['errors'] = [$e->getMessage()];
            }
        } else {
            $_SESSION['errors'] = $errors;
        }
        
        header('Location: index.php?action=admin_metiers');
        exit;
    }
    
    public function editForm(int $id): void {
        $metier = $this->metierManager->findById($id);
        $categories = $this->categoryManager->findAll();
        
        if (!$metier) {
            header('Location: index.php?action=admin_metiers');
            exit;
        }
        
        require_once __DIR__ . '/../views/back/metiers/edit.php';
    }
    
    public function edit(int $id): void {
        session_start();
        $errors = [];
        
        if (empty($_POST['title'])) {
            $errors[] = "Le titre du métier est obligatoire";
        }
        
        if (empty($errors)) {
            try {
                $metier = new Metier([
                    'id' => $id,
                    'title' => $_POST['title'],
                    'description' => $_POST['description'] ?? '',
                    'competences' => $_POST['competences'] ?? '',
                    'specialites' => $_POST['specialites'] ?? '',
                    'salaire' => $_POST['salaire'] ?? null,
                    'category_id' => $_POST['category_id'] ?? null
                ]);
                
                $this->metierManager->update($metier, true);
                $_SESSION['success'] = "Métier modifié avec succès";
            } catch (Exception $e) {
                $_SESSION['errors'] = [$e->getMessage()];
            }
        } else {
            $_SESSION['errors'] = $errors;
        }
        
        header('Location: index.php?action=admin_metiers');
        exit;
    }
    
    public function delete(int $id): void {
        session_start();
        $this->metierManager->delete($id, true);
        $_SESSION['success'] = "Métier supprimé avec succès";
        header('Location: index.php?action=admin_metiers');
        exit;
    }
}
?>