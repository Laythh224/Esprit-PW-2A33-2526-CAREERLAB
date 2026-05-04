<?php
declare(strict_types=1);

require __DIR__ . '/database/db.php';

// Simple MVC bootstrap (no framework)
require __DIR__ . '/app/Model/Post.php';
require __DIR__ . '/app/Model/Challenge.php';
require __DIR__ . '/app/Model/ChallengeComment.php';
require __DIR__ . '/app/Controller/PostController.php';
require __DIR__ . '/app/Controller/ChallengeController.php';

try {
    $pdo = career_lab_pdo();
} catch (Throwable $e) {
    http_response_code(500);
    echo '<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>Erreur de configuration</title>';
    echo '<style>body{font-family:Arial,sans-serif;background:#f5f7fb;padding:24px} .box{max-width:860px;margin:0 auto;background:#fff;border:1px solid #ddd;border-radius:10px;padding:18px} code{background:#f0f0f0;padding:2px 6px;border-radius:4px}</style>';
    echo '</head><body><div class="box">';
    echo '<h2>Erreur de configuration de la base de données</h2>';
    echo '<p>Le fichier <code>database/config.php</code> est manquant ou invalide.</p>';
    echo '<p>Copiez <code>database/config.example.php</code> vers <code>database/config.php</code>, puis remplissez les identifiants MySQL.</p>';
    echo '<p><strong>Détail technique:</strong> ' . htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p>';
    echo '</div></body></html>';
    exit;
}

$c = isset($_GET['c']) ? (string)$_GET['c'] : 'post';
$a = isset($_GET['a']) ? (string)$_GET['a'] : 'index';

$routes = [
    'post' => [
        'index' => [PostController::class, 'index'],
        'show' => [PostController::class, 'show'],
        'create' => [PostController::class, 'create'],
        'upvote' => [PostController::class, 'upvote'],
    ],
    'challenge' => [
        'index' => [ChallengeController::class, 'index'],
        'show' => [ChallengeController::class, 'show'],
        'create' => [ChallengeController::class, 'create'],
        'comments' => [ChallengeController::class, 'comments'],
        'commentCreate' => [ChallengeController::class, 'commentCreate'],
        'commentUpvote' => [ChallengeController::class, 'commentUpvote'],
        'saveBackoffice' => [ChallengeController::class, 'saveBackoffice'],
        'listBackoffice' => [ChallengeController::class, 'listBackoffice'],
        'saveCommentBackoffice' => [ChallengeController::class, 'saveCommentBackoffice'],
        'sendNotification' => [ChallengeController::class, 'sendNotification'],
    ],
];

if (!isset($routes[$c][$a])) {
    http_response_code(404);
    echo '404';
    exit;
}

[$cls, $method] = $routes[$c][$a];
$cls::$method($pdo);

