<?php

declare(strict_types=1);

$idMetier = (int) ($idMetier ?? 0);
$questions = is_array($questions ?? null) ? $questions : [];
$dbError = isset($dbError) ? (string) $dbError : null;
$validationError = isset($validationError) ? (string) $validationError : null;
$selectedAnswers = is_array($selectedAnswers ?? null) ? $selectedAnswers : [];

$e = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Quiz metier <?php echo $idMetier; ?> - Career Lab</title>

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
        .quiz-question-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(9, 30, 62, 0.12);
        }
    </style>
</head>
<body>
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="index.php?route=team" class="navbar-brand p-0">
                <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>Career Lab</h1>
            </a>
            <div class="navbar-nav ms-auto py-0">
                <a href="index.php?route=team" class="nav-item nav-link">Choix metier</a>
            </div>
        </nav>

        <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 60px;">
            <div class="row py-5">
                <div class="col-12 text-center">
                    <h1 class="display-6 text-white">Quiz du metier <?php echo $idMetier; ?></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="container" style="max-width: 980px;">
            <?php if ($dbError !== null): ?>
                <div class="alert alert-danger" role="alert"><?php echo $e($dbError); ?></div>
            <?php endif; ?>

            <?php if ($validationError !== null): ?>
                <div class="alert alert-warning" role="alert"><?php echo $e($validationError); ?></div>
            <?php endif; ?>

            <?php if ($questions === []): ?>
                <div class="alert alert-info" role="alert">Aucune question trouvee pour ce metier.</div>
            <?php else: ?>
                <form method="post" action="index.php?route=team/submit" onsubmit="return validateQuizForm(<?php echo count($questions); ?>);" novalidate>
                    <input type="hidden" name="id_metier" value="<?php echo $idMetier; ?>">

                    <?php foreach ($questions as $index => $pack): ?>
                        <?php
                            $question = $pack['question'];
                            $reponses = $pack['reponses'];
                            $questionId = (int) ($question->getId() ?? 0);
                        ?>
                        <section class="card quiz-question-card mb-3 question-card">
                            <div class="card-body p-4">
                                <h2 class="h5 mb-3"><?php echo ((int) $index + 1) . '. ' . $e($question->getTexte()); ?></h2>

                                <?php foreach ($reponses as $reponse): ?>
                                    <?php
                                        $reponseId = (int) ($reponse->getId() ?? 0);
                                        $radioId = 'q' . $questionId . '_r' . $reponseId;
                                        $isChecked = isset($selectedAnswers[$questionId]) && (int) $selectedAnswers[$questionId] === $reponseId;
                                    ?>
                                    <div class="form-check mb-2">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            id="<?php echo $radioId; ?>"
                                            name="answers[<?php echo $questionId; ?>]"
                                            value="<?php echo $reponseId; ?>"
                                            <?php echo $isChecked ? 'checked' : ''; ?>
                                            required
                                        >
                                        <label class="form-check-label" for="<?php echo $radioId; ?>">
                                            <?php echo $e($reponse->getTexte()); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endforeach; ?>

                    <button class="btn btn-success btn-lg w-100" type="submit">Valider mes reponses</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/validation.js"></script>
</body>
</html>
