<?php
$activeAccountPage = 'verification';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vérification des comptes</title>
    <link rel="stylesheet" href="Views/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="Views/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="Views/assets/css/kaiadmin.min.css" />
    <script src="Views/assets/js/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["Views/assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>
    <style>
        body {
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.18), transparent 32%),
                radial-gradient(circle at right top, rgba(16, 185, 129, 0.12), transparent 28%),
                #f4f7fb;
        }
        .page-shell {
            max-width: 1180px;
            margin: 0 auto;
        }
        .verification-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 52%, #0ea5e9 100%);
            color: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.18);
            position: relative;
            overflow: hidden;
        }
        .verification-hero::after {
            content: '';
            position: absolute;
            inset: auto -60px -80px auto;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }
        .verification-hero .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            font-size: 0.875rem;
            margin-bottom: 14px;
        }
        .verification-card {
            border: 0;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
            border-radius: 18px;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        .verification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        }
        .verification-summary {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 16px;
            padding: 14px 16px;
            min-width: 210px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <?php include __DIR__ . '/components/account-sidebar.php'; ?>
    <div class="main-panel">
        <?php include __DIR__ . '/components/account-header.php'; ?>
        <div class="container py-4 page-shell">
            <div class="verification-hero p-4 p-lg-5 mb-4">
                <div class="position-relative d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-center">
                    <div>
                        <div class="hero-chip"><i class="fas fa-shield-alt"></i> Centre de confiance</div>
                        <h1 class="mb-2 fw-bold">Vérification des comptes</h1>
                        <p class="mb-0 text-white-50" style="max-width: 680px;">Accès rapide aux écrans de vérification, à l’historique des actions et au badge de confiance pour les comptes utilisateurs, formateurs et entreprises.</p>
                    </div>
                    <div class="verification-summary">
                        <div class="text-white-50 small text-uppercase mb-1">Raccourcis</div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="index.php?page=gestion-utilisateurs" class="btn btn-light btn-sm">Utilisateurs</a>
                            <a href="index.php?page=gestion-formateurs" class="btn btn-outline-light btn-sm">Formateurs</a>
                            <a href="index.php?page=gestion-entreprises" class="btn btn-outline-light btn-sm">Entreprises</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card verification-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="avatar avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Utilisateurs</h5>
                                    <small class="text-muted">Vérifier ou retirer le badge</small>
                                </div>
                            </div>
                            <p class="text-muted mb-4">Gérez le statut vérifié des comptes utilisateurs et envoyez automatiquement la notification email.</p>
                            <a href="index.php?page=gestion-utilisateurs" class="btn btn-primary w-100">Ouvrir la gestion utilisateurs</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card verification-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="avatar avatar-lg bg-success text-white rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Formateurs</h5>
                                    <small class="text-muted">Badge vérifié côté listing</small>
                                </div>
                            </div>
                            <p class="text-muted mb-4">Accédez au tableau de gestion des formateurs pour vérifier leurs profils et suivre l’historique.</p>
                            <a href="index.php?page=gestion-formateurs" class="btn btn-success w-100">Ouvrir la gestion formateurs</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card verification-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="avatar avatar-lg bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Entreprises</h5>
                                    <small class="text-muted">Validation du compte société</small>
                                </div>
                            </div>
                            <p class="text-muted mb-4">Ouvrez la vue des entreprises pour activer ou retirer le badge de confiance sur les comptes société.</p>
                            <a href="index.php?page=gestion-entreprises" class="btn btn-warning w-100">Ouvrir la gestion entreprises</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="Views/assets/js/jquery-3.7.1.min.js"></script>
<script src="Views/assets/js/popper.min.js"></script>
<script src="Views/assets/js/bootstrap.min.js"></script>
<script src="Views/assets/js/kaiadmin.min.js"></script>
</body>
</html>
