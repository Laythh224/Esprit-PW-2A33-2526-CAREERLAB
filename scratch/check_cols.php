<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=plateforme_unique', 'root', '');
    $cols = $pdo->query('DESCRIBE entreprise')->fetchAll(PDO::FETCH_COLUMN);
    echo implode(", ", $cols);
} catch (Exception $e) {
    echo $e->getMessage();
}
