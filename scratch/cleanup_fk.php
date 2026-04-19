<?php
require_once __DIR__ . '/../config/Database.php';
$pdo = Database::getInstance()->getConnection();

try {
    // Suppression de la contrainte redondante
    $pdo->exec("ALTER TABLE OpportuniteTravail DROP FOREIGN KEY fk_experience_id");
    
    echo "Succès : La contrainte redondante 'fk_experience_id' a été supprimée. La jointure propre 'fk_experience' est maintenant l'unique référence.";
} catch (Exception $e) {
    echo "Erreur lors du nettoyage : " . $e->getMessage();
}
?>
