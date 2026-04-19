<?php
require_once 'config/Database.php';
$pdo = Database::getInstance()->getConnection();
$res = $pdo->query('DESCRIBE OpportuniteTravail')->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
foreach($res as $r) {
    echo sprintf("%-15s %-15s %-10s %-5s %s\n", $r['Field'], $r['Type'], $r['Null'], $r['Key'], $r['Default']);
}
echo "</pre>";
?>
