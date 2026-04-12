<?php
/**
 * Routeur frontal (Front Controller)
 * Point d'entrée unique pour toutes les actions PHP du projet.
 * Usage : index.php?action=nomAction
 */

require_once __DIR__ . '/controllers/OffreController.php';
require_once __DIR__ . '/controllers/CandidatureController.php';

$action = $_GET['action'] ?? '';

switch ($action) {
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

    case 'postuler':
        $controller = new CandidatureController();
        $controller->postuler();
        break;

    default:
        // Si aucune action reconnue, on redirige vers la page d'accueil
        header("Location: indexF.html");
        exit();
}
?>
