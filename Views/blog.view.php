<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/inc/app-paths.php';
$pageTitle = 'Blog';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> — CareerLab</title>
    <link href="<?= htmlspecialchars(careerlabb_asset('Views/assets/css/bootstrap.min.css'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" rel="stylesheet">
    <link href="<?= htmlspecialchars(careerlabb_asset('Views/assets/css/style.css'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= htmlspecialchars(careerlabb_route('page=accueil'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">CareerLab</a>
            <span class="navbar-text text-white-50 small">Blog</span>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="mb-3"><?= htmlspecialchars($pageTitle, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h1>
                        <p class="lead text-muted mb-4">Articles et actualités : cette section sera enrichie prochainement.</p>
                        <a href="<?= htmlspecialchars(careerlabb_route('page=accueil'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="btn btn-primary">Retour à l’accueil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
