<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'mon_site');
define('DB_USER', 'root');
define('DB_PASS', '');

// Connexion à la base de données
function getDBConnection() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

// Création de la table metiers si elle n'existe pas
function createTableIfNotExists() {
    $pdo = getDBConnection();
    $sql = "CREATE TABLE IF NOT EXISTS metiers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        description TEXT,
        salaire_moyen DECIMAL(10,2),
        secteur VARCHAR(100),
        date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_nom (nom),
        INDEX idx_secteur (secteur)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($sql);
}

// Fonction de diagnostic pour vérifier la configuration
function diagnosticDatabase() {
    $results = [];

    try {
        // Test de connexion
        $pdo = getDBConnection();
        $results['connection'] = ['status' => 'success', 'message' => 'Connexion réussie'];

        // Vérifier si la base existe
        $stmt = $pdo->query("SELECT DATABASE() as db_name");
        $db = $stmt->fetch(PDO::FETCH_ASSOC);
        $results['database'] = ['status' => 'success', 'message' => 'Base de données : ' . $db['db_name']];

        // Vérifier si la table existe
        $stmt = $pdo->query("SHOW TABLES LIKE 'metiers'");
        $tableExists = $stmt->rowCount() > 0;
        if ($tableExists) {
            $results['table'] = ['status' => 'success', 'message' => 'Table metiers existe'];
        } else {
            createTableIfNotExists();
            $results['table'] = ['status' => 'warning', 'message' => 'Table metiers créée'];
        }

        // Compter les enregistrements
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM metiers");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $results['records'] = ['status' => 'info', 'message' => $count . ' enregistrement(s) trouvé(s)'];

    } catch (PDOException $e) {
        $results['error'] = ['status' => 'error', 'message' => 'Erreur : ' . $e->getMessage()];
    }

    return $results;
}

// Initialisation
createTableIfNotExists();
?>