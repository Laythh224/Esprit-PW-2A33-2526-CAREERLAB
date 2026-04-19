<?php
require_once __DIR__ . '/../config/Database.php';
$pdo = Database::getInstance()->getConnection();

try {
    $pdo->exec("ALTER TABLE OpportuniteTravail DROP COLUMN IF EXISTS date_publication");
    $pdo->exec("ALTER TABLE OpportuniteTravail DROP COLUMN IF EXISTS travail_id");
    $pdo->exec("ALTER TABLE OpportuniteTravail DROP COLUMN IF EXISTS niveau_experience");
    
    echo "Migration réussie : Colonnes supprimées version 2.";
} catch (Exception $e) {
    echo "Erreur lors de la migration : " . $e->getMessage();
}
?>
