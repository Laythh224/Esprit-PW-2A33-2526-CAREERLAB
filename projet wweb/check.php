<?php
require_once __DIR__ . '/models/Database.php';
$pdo = Database::getInstance()->getConnection();
$stmt = $pdo->query('
    SELECT c.id, c.offre_id, c.nom_candidat, t.entreprise, t.titre 
    FROM Candidature c 
    JOIN OpportuniteTravail t ON c.offre_id = t.id
');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
