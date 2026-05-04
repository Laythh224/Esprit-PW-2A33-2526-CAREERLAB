<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'mon_site');  // ← CHANGÉ : utilise votre base existante
define('DB_USER', 'root');
define('DB_PASS', '');

// Connexion PDO (obligatoire)
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch(PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

// Création automatique des tables (seulement si elles n'existent pas)
function initDatabase() {
    $pdo = getDBConnection();
    
    // Table category (si elle n'existe pas déjà)
    $pdo->exec("CREATE TABLE IF NOT EXISTS category (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    
    // Table job (metier) (si elle n'existe pas déjà)
    $pdo->exec("CREATE TABLE IF NOT EXISTS job (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        salaire DECIMAL(10,2),
        category_id INT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE SET NULL,
        INDEX idx_title (title)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    
    // Insertion catégories par défaut (seulement si la table est vide)
    $stmt = $pdo->query("SELECT COUNT(*) FROM category");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO category (name) VALUES 
            ('Technologie'), ('Santé'), ('Finance'), ('Éducation'), ('Commerce'), ('Industrie')");
    }
    
    // Insertion métiers de démonstration (seulement si la table est vide)
    $stmt = $pdo->query("SELECT COUNT(*) FROM job");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO job (title, description, salaire, category_id) VALUES 
            ('Développeur Web', 'Conception et développement d\'applications web modernes', 35000, 1),
            ('Designer Graphique', 'Création de supports visuels et graphiques', 28000, 5),
            ('Ingénieur Civil', 'Conception et supervision de projets de construction', 45000, 6),
            ('Médecin Généraliste', 'Pratique médicale générale et prévention', 55000, 2),
            ('Professeur des Écoles', 'Enseignement primaire et éducation des enfants', 28000, 4),
            ('Comptable', 'Gestion comptable et financière des entreprises', 32000, 3)");
    }
}

initDatabase();
?>