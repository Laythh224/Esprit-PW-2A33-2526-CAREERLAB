<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$aiSupportFlash = $_SESSION['ai_support_flash'] ?? null;
if ($aiSupportFlash !== null) {
    unset($_SESSION['ai_support_flash']);
}
$aiSupportOld = is_array($aiSupportFlash['old'] ?? null) ? $aiSupportFlash['old'] : [];
$aiSupportErrors = is_array($aiSupportFlash['errors'] ?? null) ? $aiSupportFlash['errors'] : [];

require_once dirname(__DIR__, 2) . '/Views/inc/app-paths.php';

$pageTitle = 'E-learning';
/** @var array<int, array<string, mixed>> $formations */
/** @var array<int, array<string, mixed>> $sessions */
/** @var array<int, string> $partialErrors */

$h = static function (?string $s): string {
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};

$fmtDate = static function (?string $d) use ($h): string {
    if ($d === null || $d === '') {
        return '—';
    }
    $t = strtotime($d);

    return $t ? date('d/m/Y', $t) : $h($d);
};

$metiersUrl = careerlabb_asset('startup2-1.0.0/index.php') . '?action=metiers';
$evalUrl = careerlabb_asset('projet_web/index.php') . '?route=team';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title><?= $h($pageTitle) ?> — CareerLab</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="<?= $h(careerlabb_asset('Views/assets/img/favicon.ico')) ?>" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="<?= $h(careerlabb_asset('Views/assets/css/owl.carousel.min.css')) ?>" rel="stylesheet">
    <link href="<?= $h(careerlabb_asset('Views/assets/css/animate.min.css')) ?>" rel="stylesheet">

    <link href="<?= $h(careerlabb_asset('Views/assets/css/bootstrap.min.css')) ?>" rel="stylesheet">
    <link href="<?= $h(careerlabb_asset('Views/assets/css/style.css')) ?>" rel="stylesheet">
    <style>
        .navbar-user-line {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-profile-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            margin-left: 1rem;
            margin-right: 0.5rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .navbar-profile-btn:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.14);
            border-color: rgba(255, 255, 255, 0.28);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.16);
        }

        .navbar-verified-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 999px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #fff;
            font-size: 12px;
            line-height: 1;
            box-shadow: 0 6px 14px rgba(59, 130, 246, 0.32);
            flex: 0 0 auto;
        }

        .ai-support-response {
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 8px;
            padding: 14px;
            text-align: left;
        }

        .e-session-online { border-left: 4px solid var(--primary); }
        .e-session-presentiel { border-left: 4px solid var(--secondary); }

        .e-learn-stat {
            border-radius: 16px;
            border: 0;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
        }

        .e-learn-card {
            border-radius: 16px;
            border: 0;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }

        .e-learn-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 22px 55px rgba(15, 23, 42, 0.12);
        }

        .e-learn-table-wrap {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.07);
        }
    </style>
</head>

<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner"></div>
    </div>

    <div class="container-fluid bg-dark px-5 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>Esprit, Ghazela</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+216 98 542 321</small>
                    <small class="text-light"><i class="fa fa-envelope-open me-2"></i>careerlab@gmail.com</small>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#" aria-label="Twitter"><i class="fab fa-twitter fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#" aria-label="Facebook"><i class="fab fa-facebook-f fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#" aria-label="Instagram"><i class="fab fa-instagram fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle" href="#" aria-label="YouTube"><i class="fab fa-youtube fw-normal"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="<?= $h(careerlabb_route('page=accueil')) ?>" class="navbar-brand p-0">
                <img src="<?= $h(careerlabb_asset('Views/assets/img/image_2026-04-11_005109464-removebg-preview.png')) ?>" alt="CareerLab" style="height: 52px; max-width: 100%;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapseElearn" aria-controls="navbarCollapseElearn" aria-expanded="false" aria-label="Menu">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapseElearn">
                <div class="navbar-nav ms-auto py-0">
                    <a href="<?= $h(careerlabb_route('page=accueil')) ?>" class="nav-item nav-link">Accueil</a>
                    <a href="<?= $h($metiersUrl) ?>" class="nav-item nav-link">Métiers</a>
                    <a href="<?= $h($evalUrl) ?>" class="nav-item nav-link">Évaluation</a>
                    <a href="<?= $h(careerlabb_route('page=offres')) ?>" class="nav-item nav-link">Offres</a>
                    <a href="<?= $h(careerlabb_route('page=e-learnings')) ?>" class="nav-item nav-link active">E-learning</a>
                    <a href="<?= $h(careerlabb_route('page=blog')) ?>" class="nav-item nav-link">Blog</a>
                </div>
                <?php if (isset($_SESSION['nom']) && $_SESSION['nom'] !== '') : ?>
                    <a href="<?= $h(careerlabb_route('page=profile')) ?>" class="navbar-profile-btn navbar-user-line">
                        <span><?= $h((string) $_SESSION['nom']) ?></span>
                        <?php if (!empty($_SESSION['account_verified'])) : ?>
                            <span class="navbar-verified-badge" title="Compte vérifié">✔</span>
                        <?php endif; ?>
                    </a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                        <a href="<?= $h(careerlabb_route('page=dashboard-admin')) ?>" class="btn btn-warning py-2 px-3 me-2">BackOffice</a>
                    <?php endif; ?>
                    <a href="<?= $h(careerlabb_route('page=logout')) ?>" class="btn btn-outline-light py-2 px-3">Se déconnecter</a>
                <?php else : ?>
                    <a href="<?= $h(careerlabb_route('page=login')) ?>" class="btn btn-outline-light py-2 px-3 ms-lg-3 me-2">Se connecter</a>
                    <a href="<?= $h(careerlabb_route('page=creer-compte')) ?>" class="btn btn-primary py-2 px-3">Créer un compte</a>
                <?php endif; ?>
            </div>
        </nav>

        <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 0;">
            <div class="container py-4">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-9">
                        <h5 class="fw-bold text-white text-uppercase mb-2">CareerLab</h5>
                        <h1 class="display-4 text-white mb-3"><?= $h($pageTitle) ?></h1>
                        <p class="lead text-white-50 mb-0">Catalogue des formations et sessions planifiées — données synchronisées avec votre base.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($partialErrors !== []) : ?>
        <div class="container py-3">
            <div class="alert alert-warning border-0 shadow-sm mb-0" role="alert">
                Certaines sections n’ont pas pu être chargées : <?= $h(implode(', ', $partialErrors)) ?>.
                Vérifiez que les tables <code>formation</code> et <code>session</code> existent dans MySQL.
            </div>
        </div>
    <?php endif; ?>

    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-2">
            <div class="row g-4">
                <div class="col-sm-6 col-lg-5">
                    <div class="card e-learn-stat h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary rounded d-inline-flex align-items-center justify-content-center text-white" style="width: 56px; height: 56px;">
                                    <i class="fa fa-book fa-lg"></i>
                                </div>
                                <div>
                                    <div class="text-muted small text-uppercase fw-bold">Formations référencées</div>
                                    <div class="display-5 fw-bold text-primary lh-1"><?= count($formations) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-5">
                    <div class="card e-learn-stat h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary rounded d-inline-flex align-items-center justify-content-center text-white" style="width: 56px; height: 56px;">
                                    <i class="fa fa-calendar-alt fa-lg"></i>
                                </div>
                                <div>
                                    <div class="text-muted small text-uppercase fw-bold">Sessions planifiées</div>
                                    <div class="display-5 fw-bold text-primary lh-1"><?= count($sessions) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5 bg-light wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-2">
            <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 700px;">
                <h5 class="fw-bold text-primary text-uppercase">Catalogue</h5>
                <h1 class="mb-0">Formations disponibles</h1>
            </div>
            <?php if (count($formations) === 0) : ?>
                <p class="text-muted text-center mb-0">Aucune entrée dans la table <code>formation</code>.</p>
            <?php else : ?>
                <div class="row g-4">
                    <?php foreach ($formations as $f) : ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="card e-learn-card h-100">
                                <div class="card-body d-flex flex-column p-4">
                                    <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                                        <span class="badge bg-light text-primary fw-semibold px-3 py-2"><?= $h($f['specialite'] ?? '') ?></span>
                                        <span class="text-primary"><i class="fa fa-graduation-cap"></i></span>
                                    </div>
                                    <h3 class="h5 fw-bold mb-3"><?= $h($f['nom_formation'] ?? '') ?></h3>
                                    <p class="text-muted flex-grow-1 mb-3"><?= nl2br($h($f['description'] ?? '')) ?></p>
                                    <div class="pt-2 border-top">
                                        <span class="text-secondary small"><strong class="text-dark">Niveau :</strong> <?= $h($f['niveau'] ?? '—') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-2">
            <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 700px;">
                <h5 class="fw-bold text-primary text-uppercase">Planning</h5>
                <h1 class="mb-0">Sessions ouvertes</h1>
            </div>
            <?php if (count($sessions) === 0) : ?>
                <p class="text-muted text-center mb-0">Aucune session dans la table <code>session</code>.</p>
            <?php else : ?>
                <div class="e-learn-table-wrap bg-white">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Formation</th>
                                    <th>Modalité</th>
                                    <th>Période</th>
                                    <th>Détail</th>
                                    <th>Places</th>
                                    <th class="pe-4 text-end"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sessions as $s) :
                                    $type = strtolower((string) ($s['type'] ?? ''));
                                    $isOnline = $type === 'online';
                                    $rowClass = $isOnline ? 'e-session-online' : 'e-session-presentiel';
                                    $lien = trim((string) ($s['lien'] ?? ''));
                                    ?>
                                    <tr class="<?= $h($rowClass) ?>">
                                        <td class="fw-semibold ps-4"><?= $h($s['nom_formation'] ?? '') ?></td>
                                        <td>
                                            <?php if ($isOnline) : ?>
                                                <span class="badge bg-primary">En ligne</span>
                                            <?php else : ?>
                                                <span class="badge bg-success">Présentiel</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-nowrap small"><?= $fmtDate($s['date_debut'] ?? null) ?> → <?= $fmtDate($s['date_fin'] ?? null) ?></td>
                                        <td class="small">
                                            <?php if ($isOnline) : ?>
                                                <?php if ($s['duree_online'] !== null && $s['duree_online'] !== '') : ?>
                                                    <span class="text-muted"><?= $h((string) $s['duree_online']) ?> h</span>
                                                <?php endif; ?>
                                                <?php if ($lien !== '') : ?>
                                                    <div><a href="<?= $h($lien) ?>" target="_blank" rel="noopener noreferrer">Lien session</a></div>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <?php if (($s['adresse'] ?? '') !== '') : ?>
                                                    <div><?= $h((string) $s['adresse']) ?></div>
                                                <?php endif; ?>
                                                <?php if (($s['salle'] ?? '') !== '') : ?>
                                                    <div class="text-muted">Salle <?= $h((string) $s['salle']) ?></div>
                                                <?php endif; ?>
                                                <?php if ($s['duree_presentiel'] !== null && $s['duree_presentiel'] !== '') : ?>
                                                    <div class="text-muted"><?= $h((string) $s['duree_presentiel']) ?> h présentiel</div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $h((string) ($s['nb_place'] ?? '—')) ?></td>
                                        <td class="text-end pe-4">
                                            <?php if ($isOnline && $lien !== '') : ?>
                                                <a class="btn btn-sm btn-primary rounded-pill px-3" href="<?= $h($lien) ?>" target="_blank" rel="noopener noreferrer">Ouvrir</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <div class="text-center mt-5">
                <a href="<?= $h(careerlabb_route('page=accueil')) ?>" class="btn btn-outline-primary py-3 px-5 me-2 mb-2">Retour à l’accueil</a>
                <a href="<?= $h(careerlabb_route('page=dashboard')) ?>" class="btn btn-primary py-3 px-5 mb-2">Mon espace</a>
            </div>
        </div>
    </div>

    <div id="site-footer" class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-4 col-md-6 footer-about">
                    <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 bg-primary p-4">
                        <a href="<?= $h(careerlabb_route('page=accueil')) ?>" class="navbar-brand">
                            <h1 class="m-0 text-white"><i class="fa fa-user-tie me-2"></i>CareerLab</h1>
                        </a>
                        <p class="mt-3 mb-4">Décrivez votre problème et l’assistant IA CareerLab vous propose une réponse adaptée.</p>
                        <form id="support-ia" action="<?= $h(careerlabb_route('page=ai-support')) ?>" method="POST" class="w-100 text-start" novalidate>
                            <?php if ($aiSupportFlash !== null) : ?>
                                <div class="alert alert-<?= $h((string) ($aiSupportFlash['type'] ?? 'info')) ?> py-2">
                                    <?= $h((string) ($aiSupportFlash['message'] ?? '')) ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($aiSupportFlash['ai_response'])) : ?>
                                <div class="ai-support-response mb-3">
                                    <strong>Réponse IA :</strong>
                                    <p class="mb-0 mt-2"><?= nl2br($h((string) $aiSupportFlash['ai_response'])) ?></p>
                                </div>
                            <?php endif; ?>
                            <div class="mb-2">
                                <input type="text" name="name" class="form-control border-white p-3" placeholder="Votre nom (optionnel)" value="<?= $h((string) ($aiSupportOld['name'] ?? '')) ?>">
                                <?php if (($aiSupportErrors['name'] ?? '') !== '') : ?>
                                    <small class="text-white d-block mt-1"><?= $h((string) $aiSupportErrors['name']) ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="email" class="form-control border-white p-3" placeholder="Votre email" value="<?= $h((string) ($aiSupportOld['email'] ?? '')) ?>">
                                <?php if (($aiSupportErrors['email'] ?? '') !== '') : ?>
                                    <small class="text-white d-block mt-1"><?= $h((string) $aiSupportErrors['email']) ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <textarea name="message" class="form-control border-white p-3" rows="4" placeholder="Expliquez votre problème"><?= $h((string) ($aiSupportOld['message'] ?? '')) ?></textarea>
                                <?php if (($aiSupportErrors['message'] ?? '') !== '') : ?>
                                    <small class="text-white d-block mt-1"><?= $h((string) $aiSupportErrors['message']) ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-dark" type="submit">Envoyer à l’assistant IA</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <div class="row gx-5">
                        <div class="col-lg-4 col-md-12 pt-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Contact</h3>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                <p class="mb-0">Esprit, Ghazela</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-envelope-open text-primary me-2"></i>
                                <p class="mb-0">careerlab@gmail.com</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-telephone text-primary me-2"></i>
                                <p class="mb-0">+216 98 542 321</p>
                            </div>
                            <div class="d-flex mt-4">
                                <a class="btn btn-primary btn-square me-2" href="#" aria-label="Twitter"><i class="fab fa-twitter fw-normal"></i></a>
                                <a class="btn btn-primary btn-square me-2" href="#" aria-label="Facebook"><i class="fab fa-facebook-f fw-normal"></i></a>
                                <a class="btn btn-primary btn-square me-2" href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in fw-normal"></i></a>
                                <a class="btn btn-primary btn-square" href="#" aria-label="Instagram"><i class="fab fa-instagram fw-normal"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Liens rapides</h3>
                            </div>
                            <div class="link-animated d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="<?= $h(careerlabb_route('page=accueil')) ?>"><i class="bi bi-arrow-right text-primary me-2"></i>Accueil</a>
                                <a class="text-light mb-2" href="<?= $h(careerlabb_route('page=offres')) ?>"><i class="bi bi-arrow-right text-primary me-2"></i>Offres</a>
                                <a class="text-light mb-2" href="<?= $h(careerlabb_route('page=e-learnings')) ?>"><i class="bi bi-arrow-right text-primary me-2"></i>E-learning</a>
                                <a class="text-light mb-2" href="<?= $h(careerlabb_route('page=blog')) ?>"><i class="bi bi-arrow-right text-primary me-2"></i>Blog</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Navigation</h3>
                            </div>
                            <div class="link-animated d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="<?= $h($metiersUrl) ?>"><i class="bi bi-arrow-right text-primary me-2"></i>Métiers</a>
                                <a class="text-light mb-2" href="<?= $h($evalUrl) ?>"><i class="bi bi-arrow-right text-primary me-2"></i>Évaluation</a>
                                <a class="text-light mb-2" href="<?= $h(careerlabb_route('page=login')) ?>"><i class="bi bi-arrow-right text-primary me-2"></i>Connexion</a>
                                <a class="text-light" href="<?= $h(careerlabb_route('page=creer-compte')) ?>"><i class="bi bi-arrow-right text-primary me-2"></i>Créer un compte</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid text-white" style="background: #061429;">
        <div class="container text-center">
            <div class="row justify-content-end">
                <div class="col-lg-8 col-md-6">
                    <div class="d-flex align-items-center justify-content-center flex-wrap gap-2" style="min-height: 75px;">
                        <p class="mb-0">&copy; <a class="text-white border-bottom" href="<?= $h(careerlabb_route('page=accueil')) ?>">CareerLab</a>. Tous droits réservés.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $h(careerlabb_asset('Views/assets/js/wow.min.js')) ?>"></script>
    <script src="<?= $h(careerlabb_asset('Views/assets/js/easing.min.js')) ?>"></script>
    <script src="<?= $h(careerlabb_asset('Views/assets/js/waypoints.min.js')) ?>"></script>
    <script src="<?= $h(careerlabb_asset('Views/assets/js/counterup.min.js')) ?>"></script>
    <script src="<?= $h(careerlabb_asset('Views/assets/js/owl.carousel.min.js')) ?>"></script>
    <script src="<?= $h(careerlabb_asset('Views/assets/js/main.js')) ?>"></script>
</body>

</html>
