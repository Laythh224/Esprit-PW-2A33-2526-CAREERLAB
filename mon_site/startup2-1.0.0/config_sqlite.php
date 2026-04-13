<?php
// config_sqlite.php - Configuration alternative pour SQLite (si MySQL n'est pas disponible)

// Configuration SQLite
define('DB_FILE', __DIR__ . '/gestion_metiers.db');

// Connexion à la base de données SQLite
function getSQLiteConnection() {
    try {
        $pdo = new PDO("sqlite:" . DB_FILE);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Erreur de connexion SQLite : " . $e->getMessage());
    }
}

// Création de la table metiers si elle n'existe pas
function createSQLiteTableIfNotExists() {
    $pdo = getSQLiteConnection();
    $sql = "CREATE TABLE IF NOT EXISTS metiers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nom TEXT NOT NULL,
        description TEXT,
        salaire_moyen REAL,
        secteur TEXT,
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
}

// Pour utiliser SQLite au lieu de MySQL, remplacez dans vos fichiers :
// getDBConnection() par getSQLiteConnection()
// createTableIfNotExists() par createSQLiteTableIfNotExists()

// Note : SQLite n'a pas besoin de serveur séparé et stocke tout dans un fichier
?>