<?php
// test_connection.php - Script de test pour vérifier la connexion à la base de données

require_once 'config.php';

echo "<h1>Test de connexion à la base de données</h1>";

try {
    $pdo = getDBConnection();
    echo "<p style='color: green;'>✅ Connexion à la base de données réussie !</p>";

    // Test de création de la table
    createTableIfNotExists();
    echo "<p style='color: green;'>✅ Table 'metiers' créée/vérifiée avec succès !</p>";

    // Test d'insertion de données
    $metierManager = new MetierManager();

    // Vérifier si des données existent déjà
    $existingMetiers = $metierManager->getAllMetiers();
    if (empty($existingMetiers)) {
        // Insérer des données de test
        $testData = [
            ['nom' => 'Test Développeur', 'description' => 'Test de données', 'salaire' => 30000, 'secteur' => 'Informatique'],
            ['nom' => 'Test Designer', 'description' => 'Test de données 2', 'salaire' => 25000, 'secteur' => 'Commerce']
        ];

        foreach ($testData as $data) {
            $metierManager->ajouterMetier($data['nom'], $data['description'], $data['salaire'], $data['secteur']);
        }
        echo "<p style='color: green;'>✅ Données de test insérées avec succès !</p>";
    } else {
        echo "<p style='color: blue;'>ℹ️ Des données existent déjà dans la base.</p>";
    }

    // Afficher le nombre total de métiers
    $totalMetiers = count($metierManager->getAllMetiers());
    echo "<p style='color: blue;'>ℹ️ Nombre total de métiers dans la base : <strong>$totalMetiers</strong></p>";

    // Test de recherche
    $searchResults = $metierManager->rechercherMetiers('Test');
    echo "<p style='color: blue;'>ℹ️ Résultats de recherche pour 'Test' : <strong>" . count($searchResults) . "</strong> résultat(s)</p>";

    echo "<hr>";
    echo "<p><a href='metiers.php' class='btn btn-primary'>Accéder à l'application CRUD</a></p>";
    echo "<p><a href='database_setup.sql' class='btn btn-secondary'>Voir le script SQL</a></p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<h3>Dépannage :</h3>";
    echo "<ul>";
    echo "<li>Vérifiez que MySQL est démarré</li>";
    echo "<li>Vérifiez les paramètres de connexion dans config.php</li>";
    echo "<li>Assurez-vous que la base de données 'gestion_metiers' existe</li>";
    echo "<li>Vérifiez les permissions de l'utilisateur MySQL</li>";
    echo "</ul>";
}

// Fonction pour éviter l'erreur "Cannot redeclare"
if (!function_exists('createTableIfNotExists')) {
    function createTableIfNotExists() {
        $pdo = getDBConnection();
        $sql = "CREATE TABLE IF NOT EXISTS metiers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            description TEXT,
            salaire_moyen DECIMAL(10,2),
            secteur VARCHAR(100),
            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $pdo->exec($sql);
    }
}

if (!class_exists('MetierManager')) {
    class MetierManager {
        private $pdo;

        public function __construct() {
            $this->pdo = getDBConnection();
        }

        public function ajouterMetier($nom, $description, $salaire, $secteur) {
            $sql = "INSERT INTO metiers (nom, description, salaire_moyen, secteur) VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nom, $description, $salaire, $secteur]);
        }

        public function getAllMetiers() {
            $sql = "SELECT * FROM metiers ORDER BY date_creation DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function rechercherMetiers($searchTerm) {
            $sql = "SELECT * FROM metiers WHERE nom LIKE ? OR description LIKE ? OR secteur LIKE ?";
            $stmt = $this->pdo->prepare($sql);
            $searchPattern = "%$searchTerm%";
            $stmt->execute([$searchPattern, $searchPattern, $searchPattern]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
?>