<?php
$pdo = require 'c:/xampp/htdocs/projet_web/db.php';
$qs = $pdo->query('SELECT id, texte, id_metier FROM questions ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
foreach ($qs as $q) {
    echo 'Q'.$q['id'].'|'.$q['id_metier'].'|'.$q['texte'].PHP_EOL;
    $st = $pdo->prepare('SELECT id, texte, est_correcte FROM reponses WHERE id_question=? ORDER BY id ASC');
    $st->execute([$q['id']]);
    foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
        echo '  R'.$r['id'].'|'.$r['est_correcte'].'|'.$r['texte'].PHP_EOL;
    }
}
