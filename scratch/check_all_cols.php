<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=plateforme_unique', 'root', '');
    
    echo "ENTREPRISE: " . implode(", ", $pdo->query('DESCRIBE entreprise')->fetchAll(PDO::FETCH_COLUMN)) . "\n";
    echo "UTILISATEUR: " . implode(", ", $pdo->query('DESCRIBE utilisateur')->fetchAll(PDO::FETCH_COLUMN)) . "\n";
    echo "FORMATEUR: " . implode(", ", $pdo->query('DESCRIBE formateur')->fetchAll(PDO::FETCH_COLUMN)) . "\n";
    
} catch (Exception $e) {
    echo $e->getMessage();
}
