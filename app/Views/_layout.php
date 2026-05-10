<?php
declare(strict_types=1);

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Career Lab') ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
<div class="container py-4">
    <nav class="mb-4 d-flex flex-wrap gap-2">
        <a class="btn btn-sm btn-primary" href="index.php?c=post&a=index">Posts</a>
        <a class="btn btn-sm btn-primary" href="index.php?c=challenge&a=index">Challenges</a>
        <a class="btn btn-sm btn-outline-secondary" href="indexF.html">Static Front</a>
    </nav>
    <?= $content ?? '' ?>
</div>
</body>
</html>

