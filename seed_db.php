<?php
require_once __DIR__ . '/models/Database.php';

try {
    $db = new Database();
    $pdo = $db->connection();

    // Nettoyage préalable pour éviter les doublons si on relance
    $pdo->exec("DELETE FROM reponses_utilisateurs");
    $pdo->exec("DELETE FROM reponses");
    $pdo->exec("DELETE FROM questions");

    // Insertion d'un métier de test (ID 1)
    $pdo->exec("INSERT INTO questions (id, texte, id_metier) VALUES (1, 'Quelle est la principale mission d\'un développeur Web ?', 1)");
    $pdo->exec("INSERT INTO reponses (texte, est_correcte, id_question) VALUES ('Créer des sites et applications', 1, 1)");
    $pdo->exec("INSERT INTO reponses (texte, est_correcte, id_question) VALUES ('Vendre du matériel informatique', 0, 1)");

    $pdo->exec("INSERT INTO questions (id, texte, id_metier) VALUES (2, 'Quel langage est utilisé pour le style d\'une page ?', 1)");
    $pdo->exec("INSERT INTO reponses (texte, est_correcte, id_question) VALUES ('CSS', 1, 2)");
    $pdo->exec("INSERT INTO reponses (texte, est_correcte, id_question) VALUES ('PHP', 0, 2)");

    $pdo->exec("INSERT INTO questions (id, texte, id_metier) VALUES (3, 'Que signifie HTML ?', 1)");
    $pdo->exec("INSERT INTO reponses (texte, est_correcte, id_question) VALUES ('HyperText Markup Language', 1, 3)");
    $pdo->exec("INSERT INTO reponses (texte, est_correcte, id_question) VALUES ('Hyper Tool Multi Language', 0, 3)");

    echo "Données de test insérées avec succès pour le métier 1.";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
