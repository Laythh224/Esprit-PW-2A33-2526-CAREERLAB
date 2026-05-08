<?php
$pdo = require 'projet_web/db.php';

try {
    // 1. Get some questions and their correct answers
    $stmt = $pdo->query("
        SELECT q.id as id_question, r.id as id_reponse 
        FROM questions q 
        JOIN reponses r ON q.id = r.id_question 
        WHERE r.est_correcte = 1 
        LIMIT 20
    ");
    $data = $stmt->fetchAll();

    if (empty($data)) {
        die("Error: No questions or correct answers found in the database. Please add questions first.\n");
    }

    // 2. Create some dummy attempts
    for ($i = 1; $i <= 5; $i++) {
        $token = "dummy_attempt_" . uniqid() . "_" . $i;
        $score = rand(0, 3); // Random score
        
        // Pick 3 random questions for each attempt
        $keys = array_rand($data, min(3, count($data)));
        if (!is_array($keys)) $keys = [$keys];

        foreach ($keys as $index => $key) {
            $q = $data[$key];
            // Simulate 70% chance of correct answer
            $isCorrect = (rand(1, 10) <= 7) ? 1 : 0;
            
            $stmt = $pdo->prepare("INSERT INTO reponses_utilisateurs (id_question, id_reponse, attempt_token, est_correcte) VALUES (?, ?, ?, ?)");
            $stmt->execute([$q['id_question'], $q['id_reponse'], $token, $isCorrect]);
        }
    }

    echo "Successfully inserted dummy evaluation data for 5 attempts.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
