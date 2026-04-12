<?php
require_once 'config/Database.php';

try {
    $pdo = Database::getInstance()->getConnection();
    
    $sql = "CREATE TABLE IF NOT EXISTS `Candidature` (
        `id`               INT          NOT NULL AUTO_INCREMENT,
        `offre_id`         INT          NOT NULL,
        `type_offre`       VARCHAR(20)  NOT NULL,
        `nom`              VARCHAR(100) NOT NULL,
        `email`            VARCHAR(100) NOT NULL,
        `telephone`        VARCHAR(20),
        `niveau_etudes`    VARCHAR(50),
        `motivation`       TEXT,
        `cv_filename`      VARCHAR(255),
        `date_candidature` DATETIME     DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sql);
    echo "<h2 style='color:green;'>✅ Succès : La table 'Candidature' a été créée !</h2>";
    echo "<p>Vous pouvez maintenant retourner sur votre <a href='tables/tables.php'>Backend</a>.</p>";
    
} catch (PDOException $e) {
    echo "<h2 style='color:red;'>❌ Erreur : " . $e->getMessage() . "</h2>";
}
?>
