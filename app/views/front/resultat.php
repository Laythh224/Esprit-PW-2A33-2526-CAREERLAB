<?php

declare(strict_types=1);

$score = (int) ($score ?? 0);
$totalQuestions = (int) ($totalQuestions ?? 0);
$niveau = isset($niveau) ? (string) $niveau : 'Debutant';
$idMetier = (int) ($idMetier ?? 0);
$dbError = isset($dbError) ? (string) $dbError : null;

$ratio = $totalQuestions > 0 ? (int) round(($score / $totalQuestions) * 100) : 0;
$badgeClass = 'bg-secondary';

if ($niveau === 'Intermediaire') {
    $badgeClass = 'bg-warning text-dark';
}

if ($niveau === 'Expert') {
    $badgeClass = 'bg-success';
}

$e = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Resultat Quiz - Career Lab</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid bg-primary py-5 bg-header">
        <div class="row py-5">
            <div class="col-12 text-center">
                <img src="img/career_lab.png" alt="Career Lab" style="height: 46px; width: auto; background: #ffffff; padding: 4px 8px; border-radius: 8px; margin-bottom: 14px;">
                <h1 class="display-6 text-white">Resultat du quiz</h1>
            </div>
        </div>
    </div>

    <div class="container py-5" style="max-width: 720px;">
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-body p-4 p-md-5 text-center">
                <?php if ($dbError !== null): ?>
                    <div class="alert alert-danger" role="alert"><?php echo $e($dbError); ?></div>
                <?php else: ?>
                    <p class="lead mb-2">Score total</p>
                    <h2 class="display-6 mb-3"><?php echo $score; ?> / <?php echo $totalQuestions; ?></h2>

                    <div class="progress mb-3" style="height: 12px;">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo $ratio; ?>%;" aria-valuenow="<?php echo $ratio; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <p class="mb-4">Niveau: <span class="badge <?php echo $badgeClass; ?> fs-6"><?php echo $e($niveau); ?></span></p>
                <?php endif; ?>

                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="index.php?route=team" class="btn btn-primary">Changer de metier</a>
                    <?php if ($idMetier > 0): ?>
                        <a href="index.php?route=team/quiz&id_metier=<?php echo $idMetier; ?>" class="btn btn-outline-success">Refaire le quiz</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
