<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

$pdo = getDBConnection();

require_once __DIR__ . '/controllers/MetierController.php';
require_once __DIR__ . '/controllers/CategoryController.php';

$metierController = new MetierController($pdo);
$categoryController = new CategoryController($pdo);

$action = $_GET['action'] ?? 'home';

try {
    switch ($action) {
        case 'home':
        default:
            require_once __DIR__ . '/views/front/home.php';
            break;
        
        case 'metiers':
            $metiers = $metierController->getAllMetiers();
            $categories = $categoryController->getAllCategories();
            require_once __DIR__ . '/views/front/metiers.php';
            break;
        
        case 'admin_metiers':
            $metiers = $metierController->getAllMetiers();
            require_once __DIR__ . '/views/back/metiers/list.php';
            break;
        
        case 'admin_categories':
            $categories = $categoryController->getAllCategories();
            require_once __DIR__ . '/views/back/categories/list.php';
            break;
        
        case 'search':
            $keyword = $_GET['search'] ?? '';
            $metiers = $metierController->searchMetiers($keyword);
            $categories = $categoryController->getAllCategories();
            require_once __DIR__ . '/views/front/metiers.php';
            break;
        
        case 'add_metier_public':
            $metierController->addPublic();
            break;
        
        case 'edit_metier_public_form':
            $id = (int)($_GET['id'] ?? 0);
            $metier = $metierController->getMetierById($id);
            $categories = $categoryController->getAllCategories();
            require_once __DIR__ . '/views/front/edit_metier.php';
            break;
        
        case 'edit_metier_public':
            $id = (int)($_GET['id'] ?? 0);
            $metierController->editPublic($id);
            break;
        
        case 'delete_metier_public':
            $id = (int)($_GET['id'] ?? 0);
            $metierController->deletePublic($id);
            break;
        
        case 'add_metier_form':
            $categories = $categoryController->getAllCategories();
            require_once __DIR__ . '/views/back/metiers/add.php';
            break;
        
        case 'add_metier':
            $metierController->add();
            break;
        
        case 'edit_metier_form':
            $id = (int)($_GET['id'] ?? 0);
            $metier = $metierController->getMetierById($id);
            $categories = $categoryController->getAllCategories();
            require_once __DIR__ . '/views/back/metiers/edit.php';
            break;
        
        case 'edit_metier':
            $id = (int)($_GET['id'] ?? 0);
            $metierController->edit($id);
            break;
        
        case 'delete_metier':
            $id = (int)($_GET['id'] ?? 0);
            $metierController->delete($id);
            break;
        
        case 'add_category':
            $categoryController->add();
            break;
        
        case 'delete_category':
            $id = (int)($_GET['id'] ?? 0);
            $categoryController->delete($id);
            break;
        
        case 'toggle_like':
            $id = (int)$_GET['id'];
            $session_id = session_id();
            
            $check = $pdo->prepare("SELECT id FROM job_likes WHERE job_id = :job_id AND session_id = :session_id");
            $check->execute([':job_id' => $id, ':session_id' => $session_id]);
            
            if ($check->fetch()) {
                $delete = $pdo->prepare("DELETE FROM job_likes WHERE job_id = :job_id AND session_id = :session_id");
                $delete->execute([':job_id' => $id, ':session_id' => $session_id]);
                $liked = false;
            } else {
                $insert = $pdo->prepare("INSERT INTO job_likes (job_id, session_id) VALUES (:job_id, :session_id)");
                $insert->execute([':job_id' => $id, ':session_id' => $session_id]);
                $liked = true;
            }
            
            $count = $pdo->prepare("SELECT COUNT(*) as total FROM job_likes WHERE job_id = :job_id");
            $count->execute([':job_id' => $id]);
            $total = $count->fetch(PDO::FETCH_ASSOC)['total'];
            
            $update = $pdo->prepare("UPDATE job SET likes = :likes WHERE id = :id");
            $update->execute([':likes' => $total, ':id' => $id]);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'liked' => $liked, 'likes' => $total]);
            exit;
            break;
        
        case 'get_comments':
            $id = (int)$_GET['id'];
            
            $stmt = $pdo->prepare("SELECT * FROM job_comments WHERE job_id = :job_id ORDER BY created_at DESC");
            $stmt->execute([':job_id' => $id]);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $comments_formatted = [];
            foreach ($comments as $comment) {
                $comments_formatted[] = [
                    'author' => htmlspecialchars($comment['author']),
                    'content' => htmlspecialchars($comment['content']),
                    'date' => date('d/m/Y H:i', strtotime($comment['created_at']))
                ];
            }
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'comments' => $comments_formatted]);
            exit;
            break;
        
        case 'add_comment':
            $metier_id = (int)$_POST['metier_id'];
            $author = $_POST['author'] ?? 'Anonyme';
            $content = trim($_POST['content'] ?? '');
            
            if (empty($content)) {
                echo json_encode(['success' => false, 'error' => 'Commentaire vide']);
                exit;
            }
            
            $insert = $pdo->prepare("INSERT INTO job_comments (job_id, author, content) VALUES (:job_id, :author, :content)");
            $insert->execute([
                ':job_id' => $metier_id,
                ':author' => $author,
                ':content' => $content
            ]);
            
            $count = $pdo->prepare("SELECT COUNT(*) as total FROM job_comments WHERE job_id = :job_id");
            $count->execute([':job_id' => $metier_id]);
            $total = $count->fetch(PDO::FETCH_ASSOC)['total'];
            
            $update = $pdo->prepare("UPDATE job SET comment_count = :total WHERE id = :id");
            $update->execute([':total' => $total, ':id' => $metier_id]);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'total' => $total]);
            exit;
            break;
        
        case 'detail_metier':
            $id = (int)$_GET['id'];
            
            $sql = "SELECT job.*, category.name as category_name 
                    FROM job 
                    LEFT JOIN category ON job.category_id = category.id 
                    WHERE job.id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $metier_detail = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$metier_detail) {
                $_SESSION['errors'] = ["Métier non trouvé"];
                header('Location: index.php?action=metiers');
                exit;
            }
            
            $update = $pdo->prepare("UPDATE job SET views = views + 1 WHERE id = :id");
            $update->execute([':id' => $id]);
            
            require_once __DIR__ . '/views/front/detail_metier.php';
            break;
        
        case 'postuler':
            $id = (int)$_GET['id'];
            
            $sql = "SELECT job.*, category.name as category_name 
                    FROM job 
                    LEFT JOIN category ON job.category_id = category.id 
                    WHERE job.id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $offre = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$offre) {
                $_SESSION['errors'] = ["Offre non trouvée"];
                header('Location: index.php?action=metiers');
                exit;
            }
            
            require_once __DIR__ . '/views/front/postuler.php';
            break;
        
        case 'envoyer_candidature':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $metier_id = (int)$_POST['metier_id'];
                $nom = trim($_POST['nom'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $telephone = trim($_POST['telephone'] ?? '');
                $message = trim($_POST['message'] ?? '');
                
                // Créer le dossier s'il n'existe pas
                $upload_dir = __DIR__ . '/uploads/cv/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                // Gestion du CV
                $cv_filename = null;
                if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
                    $extension = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
                    $cv_filename = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $nom) . '.' . $extension;
                    move_uploaded_file($_FILES['cv']['tmp_name'], $upload_dir . $cv_filename);
                }
                
                // Enregistrement en BDD
                $sql = "INSERT INTO candidatures (job_id, nom, email, telephone, message, cv_path) 
                        VALUES (:job_id, :nom, :email, :telephone, :message, :cv_path)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':job_id' => $metier_id,
                    ':nom' => $nom,
                    ':email' => $email,
                    ':telephone' => $telephone,
                    ':message' => $message,
                    ':cv_path' => $cv_filename
                ]);
                
                $_SESSION['success'] = "Candidature envoyée avec succès !";
                header('Location: index.php?action=detail_metier&id=' . $metier_id);
                exit;
            }
            break;
        
        case 'about':
        case 'blog':
        case 'contact':
            require_once __DIR__ . '/views/front/empty.php';
            break;
    }
} catch (Exception $e) {
    $_SESSION['errors'] = [$e->getMessage()];
    header('Location: index.php?action=admin_metiers');
    exit;
}
?>