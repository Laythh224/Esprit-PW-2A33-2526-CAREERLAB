<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/**
 * Routeur frontal (Front Controller)
 * Point d'entrée unique pour toutes les actions PHP du projet.
 * Usage : index.php?action=nomAction
 */

require_once __DIR__ . '/controllers/BaseController.php';
require_once __DIR__ . '/controllers/PageController.php';
require_once __DIR__ . '/controllers/OffreController.php';

$action = $_GET['action'] ?? 'offres';

switch ($action) {

    // Static Pages (PageController)
    case 'home':
        $controller = new PageController();
        $controller->home();
        break;
    case 'price':
        $controller = new PageController();
        $controller->price();
        break;
    case 'feature':
        $controller = new PageController();
        $controller->feature();
        break;
    case 'team':
        $controller = new PageController();
        $controller->team();
        break;
    case 'quote':
        $controller = new PageController();
        $controller->quote();
        break;
    case 'about':
        $controller = new PageController();
        $controller->about();
        break;
    case 'contact':
        $controller = new PageController();
        $controller->contact();
        break;

    // Offers logic (OffreController)
    case 'offres':
        $controller = new OffreController();
        $controller->index();
        break;
    case 'publish':
        $controller = new OffreController();
        $controller->publish();
        break;
    case 'update':
        $controller = new OffreController();
        $controller->update();
        break;
    case 'delete':
        $controller = new OffreController();
        $controller->delete();
        break;
    case 'viewOffre':
        $controller = new OffreController();
        $controller->show();
        break;
    case 'apply':
        $controller = new OffreController();
        $controller->apply();
        break;
    case 'logout':
        session_destroy();
        $redirect = $_GET['redirect'] ?? 'index.php?action=offres';
        header('Location: ' . $redirect);
        exit();
    case 'refuseCandidature':
        $controller = new OffreController();
        $controller->refuseCandidature();
        break;
    case 'acceptCandidature':
        $controller = new OffreController();
        $controller->acceptCandidature();
        break;

    default:
        // Si aucune action reconnue, retour à la liste des offres
        $controller = new OffreController();
        $controller->index();
        break;
}

?>

