<?php
require_once __DIR__ . '/models/Database.php';

try {
    $pdo = Database::getInstance()->getConnection();
    
    // Pour être sûr, on drop et on recrée
    $pdo->exec("DROP TABLE IF EXISTS Candidature");
    
    $pdo->exec("CREATE TABLE Candidature (
        id INT AUTO_INCREMENT PRIMARY KEY,
        offre_id INT NOT NULL,
        date_postulation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        statut VARCHAR(20) DEFAULT 'en_attente',
        nom_candidat VARCHAR(255) NULL,
        email_candidat VARCHAR(255) NULL,
        cv_texte TEXT NULL,
        score_test INT DEFAULT 0,
        score_ia INT DEFAULT 0,
        compatibilite INT DEFAULT 0,
        niveau VARCHAR(50) NULL,
        recommandation VARCHAR(50) NULL,
        feedback TEXT NULL
    )");
    
    echo "Table Candidature recréée avec succès avec toutes les colonnes IA.\n";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
