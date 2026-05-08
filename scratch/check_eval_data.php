<?php
require_once 'projet_web/db.php';
$pdo = require 'projet_web/db.php';

try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM reponses_utilisateurs");
    echo "Total records in reponses_utilisateurs: " . $stmt->fetchColumn() . "\n";
    
    $stmt = $pdo->query("SELECT * FROM reponses_utilisateurs LIMIT 5");
    $rows = $stmt->fetchAll();
    if (empty($rows)) {
        echo "No records found in reponses_utilisateurs.\n";
    } else {
        echo "Sample records:\n";
        print_r($rows);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
