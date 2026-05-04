<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

// try to load PDO from project db.php
$pdo = null;
try{
    $pdo = include __DIR__ . '/../../db.php';
}catch(Throwable $e){
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$lang = 'fr';
if ($method === 'POST') {
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    if (is_array($data) && !empty($data['lang'])) {
        $lang = $data['lang'];
    }
} else {
    $lang = $_GET['lang'] ?? 'fr';
}

$lang = in_array($lang, ['fr','en','ar'], true) ? $lang : 'fr';

// Choose a question to return. For demo, pick the first question by id ascending.
try{
    $stmtQ = $pdo->query('SELECT id, texte FROM questions ORDER BY id ASC LIMIT 1');
    $qRow = $stmtQ->fetch(PDO::FETCH_ASSOC);
    if (!$qRow) {
        echo json_encode(['error' => 'No question found'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $questionId = (int)$qRow['id'];
    $defaultQuestionText = $qRow['texte'];

    // fetch translation if exists
    $stmtQt = $pdo->prepare('SELECT texte FROM question_translations WHERE id_question = :qid AND lang = :lang LIMIT 1');
    $stmtQt->execute([':qid' => $questionId, ':lang' => $lang]);
    $qt = $stmtQt->fetch(PDO::FETCH_ASSOC);
    $questionText = $qt['texte'] ?? $defaultQuestionText;

    // fetch answers
    $stmtR = $pdo->prepare('SELECT r.id, r.texte as default_texte FROM reponses r WHERE r.id_question = :qid ORDER BY r.id ASC');
    $stmtR->execute([':qid' => $questionId]);
    $reps = $stmtR->fetchAll(PDO::FETCH_ASSOC);
    $reponses = [];
    if ($reps) {
        $stmtRt = $pdo->prepare('SELECT texte FROM reponse_translations WHERE id_reponse = :rid AND lang = :lang LIMIT 1');
        foreach ($reps as $r) {
            $stmtRt->execute([':rid' => $r['id'], ':lang' => $lang]);
            $rt = $stmtRt->fetch(PDO::FETCH_ASSOC);
            $reponses[] = $rt['texte'] ?? $r['default_texte'];
        }
    }

    $response = [
        'question' => $questionText,
        'reponses' => $reponses,
        'lang' => $lang,
        'dir' => $lang === 'ar' ? 'rtl' : 'ltr'
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

}catch(Throwable $e){
    http_response_code(500);
    echo json_encode(['error' => 'Query failed: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
