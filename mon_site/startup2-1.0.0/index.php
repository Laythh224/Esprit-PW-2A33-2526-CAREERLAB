<?php
/**
 * Point d'entrée principal de l'application CRUD des Métiers
 *
 * Ce fichier gère le routage des requêtes vers le contrôleur approprié
 * et inclut les vues correspondantes avec les données préparées.
 */

// Démarrer la session pour gérer les messages flash
session_start();

// Inclure la configuration de la base de données
require_once 'config.php';

// Inclure les classes du modèle et du contrôleur
require_once 'models/MetierManager.php';
require_once 'controllers/MetierController.php';

// Créer une instance du contrôleur
$controller = new MetierController();

// Traiter la requête et obtenir les données
try {
    $data = $controller->handleRequest();

    // Inclure la vue appropriée avec les données
    switch ($data['view']) {
        case 'list':
            include 'views/metiers.php';
            break;
        case 'form':
            include 'views/form.php';
            break;
        case 'stats':
            include 'views/stats.php';
            break;
        case 'error':
            include 'views/error.php';
            break;
        default:
            include 'views/metiers.php';
            break;
    }
} catch (Exception $e) {
    // En cas d'erreur générale, afficher une page d'erreur
    error_log("Erreur dans index.php: " . $e->getMessage());
    $data = [
        'view' => 'error',
        'error_message' => 'Une erreur inattendue s\'est produite. Veuillez réessayer plus tard.',
        'messages' => [],
        'errors' => []
    ];
    include 'views/error.php';
}
?>

        case 'edit':
            // Afficher le formulaire d'édition
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                header('Location: metiers.php?error=not_found');
                exit;
            }

            $metier = $controller->show($_GET['id']);
            if (!$metier) {
                header('Location: metiers.php?error=not_found');
                exit;
            }

            // Récupérer les erreurs de session si elles existent
            $errors = $_SESSION['errors'] ?? [];
            unset($_SESSION['errors']);

            include 'views/form.php';
            break;

        case 'update':
            // Traiter la mise à jour d'un métier
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                header('Location: metiers.php?error=not_found');
                exit;
            }

            $result = $controller->update($_GET['id']);
            if ($result['success']) {
                header('Location: metiers.php?success=update');
            } else {
                // En cas d'erreur, rediriger vers le formulaire avec les erreurs
                $_SESSION['errors'] = $result['errors'];
                header('Location: metiers.php?action=edit&id=' . $_GET['id'] . '&error=validation');
            }
            exit;

        case 'delete':
            // Traiter la suppression d'un métier
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                header('Location: metiers.php?error=not_found');
                exit;
            }

            $result = $controller->destroy($_GET['id']);
            if ($result['success']) {
                header('Location: metiers.php?success=delete');
            } else {
                header('Location: metiers.php?error=delete');
            }
            exit;

        case 'search':
            // Traiter la recherche
            $query = $_GET['q'] ?? '';
            $metiers = $controller->search($query);
            include 'views/metiers.php';
            break;

        case 'stats':
            // Afficher les statistiques
            $stats = $controller->stats();
            include 'views/stats.php';
            break;

        default:
            // Action non reconnue, rediriger vers la liste
            header('Location: metiers.php');
            exit;
    }
} catch (Exception $e) {
    // En cas d'erreur générale, afficher une page d'erreur
    error_log("Erreur dans index.php: " . $e->getMessage());
    echo "<!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <title>Erreur - PHP CRUD</title>
        <link rel='stylesheet' href='assets/css/bootstrap.min.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-danger'>
                <h4><i class='fas fa-exclamation-triangle me-2'></i>Erreur</h4>
                <p>Une erreur inattendue s'est produite. Veuillez réessayer plus tard.</p>
                <a href='metiers.php' class='btn btn-primary'>Retour à l'accueil</a>
            </div>
        </div>
    </body>
    </html>";
    exit;
}
?>