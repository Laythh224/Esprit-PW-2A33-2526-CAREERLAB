<?php
require_once __DIR__ . '/../config/Database.php';
$pdo = Database::getInstance()->getConnection();

try {
    // Nettoyage preventif (déjà vérifié mais au cas où)
    $pdo->exec("UPDATE OpportuniteTravail SET experience_id = NULL WHERE experience_id NOT IN (SELECT id FROM experience)");
    
    // Ajout de la contrainte
    $sql = "ALTER TABLE OpportuniteTravail 
            ADD CONSTRAINT fk_experience 
            FOREIGN KEY (experience_id) 
            REFERENCES experience(id) 
            ON DELETE SET NULL 
            ON UPDATE CASCADE";
            
    $pdo->exec($sql);
    echo "Succès : La jointure physique (clé étrangère) a été créée dans phpMyAdmin.";
} catch (Exception $e) {
    echo "Erreur lors de la création de la jointure : " . $e->getMessage();
}
?>
