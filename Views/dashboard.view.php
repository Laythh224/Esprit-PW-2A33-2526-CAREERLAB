<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <?php if (!defined('BASE_URL')) define('BASE_URL', '/mon_site/'); ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 45%, #334155 100%);
            min-height: 100vh;
            color: #e2e8f0;
        }

        .shell {
            max-width: 1100px;
            margin: 0 auto;
            padding: 48px 20px;
        }

        .hero {
            background: rgba(15, 23, 42, 0.72);
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: 24px;
            padding: 32px;
            backdrop-filter: blur(12px);
            margin-bottom: 24px;
        }

        .metric {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 24px;
            height: 100%;
        }

        .metric h2 {
            font-size: 3rem;
            margin-bottom: 0;
        }

        .btn-soft {
            background: #f59e0b;
            border: none;
            color: #111827;
            font-weight: 700;
        }

        .btn-soft:hover {
            background: #fbbf24;
            color: #111827;
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            margin-left: 10px;
            border-radius: 999px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #fff;
            font-size: 0.85rem;
            box-shadow: 0 8px 18px rgba(59, 130, 246, 0.35);
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="shell">
        <div class="hero">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <p class="text-uppercase text-warning mb-2">Tableau de bord dynamique</p>
                    <h1 class="display-6 mb-2">
                        Bonjour, <?php echo htmlspecialchars($nom); ?>
                        <?php if (!empty($isVerified)): ?>
                            <span class="verified-badge" title="Compte vérifié">✔</span>
                        <?php endif; ?>
                    </h1>
                    <p class="mb-0 text-light-emphasis">Rôle actuel: <?php echo htmlspecialchars($role); ?></p>
                </div>
                <div class="d-flex gap-2">
                    <a class="btn btn-soft" href="index.php?page=login">Changer de compte</a>
                    <a class="btn btn-outline-light" href="index.php?page=logout">Déconnexion</a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="metric">
                    <p class="text-uppercase text-info mb-1">Utilisateurs</p>
                    <h2><?php echo $totalUtilisateurs; ?></h2>
                    <p class="mb-0 text-light-emphasis">Comptes enregistrés dans la base.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric">
                    <p class="text-uppercase text-info mb-1">Formateurs</p>
                    <h2><?php echo $totalFormateurs; ?></h2>
                    <p class="mb-0 text-light-emphasis">Profils de formateurs disponibles.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric">
                    <p class="text-uppercase text-info mb-1">Entreprises</p>
                    <h2><?php echo $totalEntreprises; ?></h2>
                    <p class="mb-0 text-light-emphasis">Entreprises stockées en base.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

