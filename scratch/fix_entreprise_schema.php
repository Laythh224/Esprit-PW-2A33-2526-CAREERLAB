<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=plateforme_unique', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $queries = [
        "ALTER TABLE entreprise ADD COLUMN cv VARCHAR(255) DEFAULT NULL AFTER site_web",
        "ALTER TABLE entreprise ADD COLUMN email_verified TINYINT(1) DEFAULT 0 AFTER cv",
        "ALTER TABLE entreprise ADD COLUMN verified TINYINT(1) DEFAULT 0 AFTER email_verified",
        "ALTER TABLE entreprise ADD COLUMN verified_at DATETIME DEFAULT NULL AFTER verified",
        "ALTER TABLE entreprise ADD COLUMN verified_by INT DEFAULT NULL AFTER verified_at",
        "ALTER TABLE entreprise ADD COLUMN role VARCHAR(20) DEFAULT 'entreprise' AFTER password"
    ];

    foreach ($queries as $query) {
        try {
            $pdo->exec($query);
            echo "Success: $query\n";
        } catch (Exception $e) {
            echo "Skipped/Error: $query (" . $e->getMessage() . ")\n";
        }
    }

} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage();
}
