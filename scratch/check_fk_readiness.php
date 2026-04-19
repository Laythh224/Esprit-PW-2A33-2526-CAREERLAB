<?php
require_once __DIR__ . '/../config/Database.php';
$pdo = Database::getInstance()->getConnection();

echo "--- Engines ---\n";
$res1 = $pdo->query("SHOW TABLE STATUS WHERE Name IN ('experience', 'OpportuniteTravail')")->fetchAll(PDO::FETCH_ASSOC);
foreach($res1 as $r) echo $r['Name'] . ": " . $r['Engine'] . "\n";

echo "\n--- Invalid IDs (Orphans) ---\n";
$stmt = $pdo->query("SELECT count(*) FROM OpportuniteTravail t LEFT JOIN Experience e ON t.experience_id = e.id WHERE e.id IS NULL AND t.experience_id IS NOT NULL AND t.experience_id != 0");
echo "Invalid IDs: " . $stmt->fetchColumn() . "\n";
?>
