<?php

declare(strict_types=1);

$metiers = is_array($metiers ?? null) ? $metiers : [];
$dbError = isset($dbError) ? (string) $dbError : null;

$e = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Quiz Metier - Career Lab</title>

    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        .quiz-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 12px 32px rgba(9, 30, 62, 0.16);
        }
    </style>
</head>
<body>
    <div class="container-fluid bg-dark px-5 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>Career Lab</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+216 00 000 000</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="index.php?route=team" class="navbar-brand p-0">
                <img src="img/career_lab.png" alt="Career Lab" style="height: 44px; width: auto; background: #ffffff; padding: 4px 8px; border-radius: 8px;">
            </a>
            <div class="navbar-nav ms-auto py-0">
                <a href="index.php?route=team" class="nav-item nav-link active">Quiz</a>
                <a href="index.php?route=front" class="nav-item nav-link">Home</a>
                <a href="index.php?route=contact" class="nav-item nav-link">Contact</a>
            </div>
        </nav>

        <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
            <div class="row py-5">
                <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                    <h1 class="display-4 text-white animated zoomIn">Quiz utilisateur</h1>
                    <p class="h5 text-white">Choisissez un metier pour demarrer votre evaluation</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container" style="max-width: 760px;">
            <div class="card quiz-card">
                <div class="card-body p-4 p-md-5">
                    <?php if ($dbError !== null): ?>
                        <div class="alert alert-danger" role="alert"><?php echo $e($dbError); ?></div>
                    <?php endif; ?>

                    <?php if ($metiers === []): ?>
                        <div class="alert alert-warning mb-0" role="alert">Aucun metier disponible.</div>
                    <?php else: ?>
                        <form method="get" action="index.php" onsubmit="return validateMetierSelection();" novalidate>
                            <input type="hidden" name="route" value="team/quiz">
                            <div class="mb-4">
                                <label for="id_metier" class="form-label fw-bold">Choix du metier</label>
                                <select class="form-select form-select-lg" id="id_metier" name="id_metier" required>
                                    <option value="">-- Selectionner un metier --</option>
                                    <?php foreach ($metiers as $metier): ?>
                                        <?php
                                            $idMetier = (int) ($metier['id_metier'] ?? 0);
                                            $totalQuestions = (int) ($metier['total_questions'] ?? 0);
                                        ?>
                                        <option value="<?php echo $idMetier; ?>">
                                            Metier <?php echo $idMetier; ?> (<?php echo $totalQuestions; ?> question(s))
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button class="btn btn-primary btn-lg w-100" type="submit">Commencer le quiz</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="js/validation.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script>new WOW().init();</script>
</body>
</html>
