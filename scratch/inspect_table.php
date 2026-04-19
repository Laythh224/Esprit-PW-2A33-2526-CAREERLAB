<?php
require_once __DIR__ . '/../config/Database.php';
$pdo = Database::getInstance()->getConnection();

$res = $pdo->query("SHOW CREATE TABLE OpportuniteTravail")->fetch(PDO::FETCH_ASSOC);
echo "<pre>" . htmlspecialchars($res['Create Table']) . "</pre>";
?>
