<?php
/** @var array<int, array<string, mixed>> $metiers */
$metiers = $metiers ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metiers / Offres d'emploi</title>
    <link href="Views/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="Views/assets/css/style.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f9fc;
        }

        .metiers-hero {
            background: linear-gradient(135deg, #091e3e 0%, #0d6efd 100%);
            color: #fff;
            padding: 70px 0 50px;
        }

        .metier-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
        }

        .metier-card .card-body {
            padding: 1.5rem;
        }

        .metier-label {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #0d6efd;
            letter-spacing: 0.04em;
        }
    </style>
</head>
<body>
    <section class="metiers-hero">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">Metiers / Offres d'emploi</h1>
                    <p class="lead mb-0">Consultez les offres enregistrees dans la table <code>job</code> avec une integration MVC propre.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="index.php?page=accueil" class="btn btn-light px-4 py-2">Retour a l'accueil</a>
                </div>
            </div>
        </div>
    </section>

    <main class="container py-5">
        <?php if (empty($metiers)): ?>
            <div class="alert alert-info">Aucune offre n'est disponible pour le moment.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($metiers as $metier): ?>
                    <div class="col-12">
                        <article class="card metier-card">
                            <div class="card-body">
                                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-3">
                                    <div>
                                        <p class="metier-label mb-2">Titre</p>
                                        <h2 class="h4 mb-0">
                                            <?= htmlspecialchars((string) ($metier['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                        </h2>
                                    </div>
                                    <div class="text-lg-end">
                                        <p class="metier-label mb-2">Salaire</p>
                                        <p class="fs-5 fw-semibold mb-0">
                                            <?=
                                                ($metier['salaire'] ?? null) !== null && $metier['salaire'] !== ''
                                                    ? htmlspecialchars(number_format((float) $metier['salaire'], 2, ',', ' ') . ' TND', ENT_QUOTES, 'UTF-8')
                                                    : 'Non renseigne'
                                            ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-lg-7">
                                        <p class="metier-label mb-2">Description</p>
                                        <p class="mb-0">
                                            <?= nl2br(htmlspecialchars((string) ($metier['description'] ?? 'Aucune description disponible.'), ENT_QUOTES, 'UTF-8')) ?>
                                        </p>
                                    </div>
                                    <div class="col-lg-5">
                                        <p class="metier-label mb-2">Competences</p>
                                        <p class="mb-0">
                                            <?= nl2br(htmlspecialchars((string) ($metier['competences'] ?? 'Aucune competence renseignee.'), ENT_QUOTES, 'UTF-8')) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
