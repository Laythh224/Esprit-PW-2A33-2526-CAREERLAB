<?php
require_once __DIR__ . '/../config/Database.php';

try {
    $pdo = Database::getInstance()->getConnection();
    echo "Mise à jour des niveaux d'expérience...\n";

    // 1. Vider la table pour repartir sur vos choix précis
    // On désactive temporairement les FK pour éviter les erreurs si des offres sont déjà liées
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE Experience");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    // 2. Insérer vos choix exacts
    $sqlInsert = "INSERT INTO Experience (niveau, description) VALUES 
        ('Junior', '0 à 1 an d\'expérience'),
        ('Intermédiaire', '1 à 3 ans d\'expérience'),
        ('Confirmé', '3 à 5 ans d\'expérience'),
        ('Senior', 'Plus de 5 ans d\'expérience'),
        ('Expert', 'Plus de 8 ans avec expertise avancée')";
    
    $pdo->exec($sqlInsert);
    echo "Les nouveaux niveaux ont été ajoutés avec succès !\n";

} catch (Exception $e) {
    die("ERREUR : " . $e->getMessage() . "\n");
}
?>
