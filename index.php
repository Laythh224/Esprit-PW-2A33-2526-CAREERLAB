<?php
/**
 * Routeur frontal (Front Controller)
 * Point d'entrée unique pour toutes les actions PHP du projet.
 * Usage : index.php?action=nomAction
 */

require_once __DIR__ . '/controllers/BaseController.php';
require_once __DIR__ . '/controllers/PageController.php';
require_once __DIR__ . '/controllers/OffreController.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    // Static Pages (PageController)
    case 'home':
        $controller = new PageController();
        $controller->home();
        break;
    case 'about':
        $controller = new PageController();
        $controller->about();
        break;
    case 'contact':
        $controller = new PageController();
        $controller->contact();
        break;
    case 'feature':
        $controller = new PageController();
        $controller->feature();
        break;
    case 'team':
        $controller = new PageController();
        $controller->team();
        break;
    case 'price':
        $controller = new PageController();
        $controller->price();
        break;
    case 'blog':
        $controller = new PageController();
        $controller->blog();
        break;
    case 'detail':
        $controller = new PageController();
        $controller->detail();
        break;
    case 'quote':
        $controller = new PageController();
        $controller->quote();
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


    default:
        // Si aucune action reconnue, retour à l'accueil
        $controller = new PageController();
        $controller->home();
        break;
}

?>
