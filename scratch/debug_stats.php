<?php
require_once 'config/Database.php';
$pdo = Database::getInstance()->getConnection();

echo "--- EXPERIENCE LEVELS ---\n";
$exps = $pdo->query("SELECT * FROM Experience")->fetchAll(PDO::FETCH_ASSOC);
print_r($exps);

echo "\n--- JOBS WITH EXP_ID ---\n";
$jobs = $pdo->query("SELECT id, titre, experience_id FROM OpportuniteTravail")->fetchAll(PDO::FETCH_ASSOC);
print_r($jobs);

echo "\n--- STATS QUERY ---\n";
$stats = $pdo->query("SELECT e.niveau, COUNT(t.id) as total FROM Experience e LEFT JOIN OpportuniteTravail t ON e.id = t.experience_id GROUP BY e.id, e.niveau")->fetchAll(PDO::FETCH_ASSOC);
print_r($stats);
?>
