<?php
/**
 * Script de test pour vérifier le fonctionnement de l'application CRUD
 */

// Inclure la configuration
require_once 'config.php';

// Tester la connexion à la base de données
try {
    $pdo = getDBConnection();
    echo "✅ Connexion à la base de données réussie\n";

    // Tester la création de la table si elle n'existe pas
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS metiers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            description TEXT,
            salaire_moyen DECIMAL(10,2),
            secteur VARCHAR(100),
            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✅ Table 'metiers' créée/vérifiée\n";

    // Inclure le modèle
    require_once 'models/MetierManager.php';
    $metierManager = new MetierManager();

    // Tester l'ajout d'un métier
    $result = $metierManager->ajouterMetier(
        'Test Développeur',
        'Métier de test pour vérifier le fonctionnement',
        45000,
        'Technologie'
    );

    if ($result) {
        echo "✅ Ajout de métier test réussi\n";

        // Tester la récupération
        $metiers = $metierManager->getAllMetiers();
        echo "✅ Récupération des métiers : " . count($metiers) . " métier(s) trouvé(s)\n";

        // Tester les statistiques
        $stats = $metierManager->getStatistiques();
        echo "✅ Statistiques générées : " . $stats['total_metiers'] . " métier(s) total\n";

        // Nettoyer le test
        if (!empty($metiers)) {
            $lastMetier = end($metiers);
            if (strpos($lastMetier['nom'], 'Test') === 0) {
                $metierManager->supprimerMetier($lastMetier['id']);
                echo "✅ Métier de test supprimé\n";
            }
        }

    } else {
        echo "❌ Échec de l'ajout du métier test\n";
    }

    // Inclure le contrôleur
    require_once 'controllers/MetierController.php';
    $controller = new MetierController();

    // Tester le contrôleur
    $data = $controller->handleRequest();
    if (isset($data['view'])) {
        echo "✅ Contrôleur fonctionnel, vue : " . $data['view'] . "\n";
    } else {
        echo "❌ Problème avec le contrôleur\n";
    }

    echo "\n🎉 Tests terminés avec succès ! L'application est prête.\n";

} catch (Exception $e) {
    echo "❌ Erreur lors des tests : " . $e->getMessage() . "\n";
    exit(1);
}
?>