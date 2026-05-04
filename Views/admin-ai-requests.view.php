<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="UTF-8">
    <title>Demandes IA - CareerLab</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="Views/assets/img/favicon.ico" type="image/x-icon" />
    <script src="Views/assets/js/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
          urls: ["Views/assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>
    <link rel="stylesheet" href="Views/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="Views/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="Views/assets/css/kaiadmin.min.css" />
</head>
<body>
    <div class="wrapper">
        <?php $activeAccountPage = 'demandes-ia'; include __DIR__ . '/components/account-sidebar.php'; ?>

        <div class="main-panel">
            <?php include __DIR__ . '/components/account-header.php'; ?>

            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">Demandes utilisateurs analysees par IA</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Utilisateur</th>
                                            <th>Type</th>
                                            <th>Message original</th>
                                            <th>Reponse IA</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($requests)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">Aucune demande pour le moment.</td>
                                            </tr>
                                        <?php endif; ?>

                                        <?php foreach ($requests as $request): ?>
                                            <tr>
                                                <td><?= htmlspecialchars((string) ($request['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars((string) ($request['name'] ?: 'Utilisateur'), ENT_QUOTES, 'UTF-8') ?></strong><br>
                                                    <small class="text-muted"><?= htmlspecialchars((string) ($request['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        <?= htmlspecialchars((string) ($request['request_type'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                </td>
                                                <td style="min-width: 260px;">
                                                    <?= nl2br(htmlspecialchars((string) ($request['message'] ?? ''), ENT_QUOTES, 'UTF-8')) ?>
                                                </td>
                                                <td style="min-width: 300px;">
                                                    <?= nl2br(htmlspecialchars((string) ($request['ai_response'] ?? ''), ENT_QUOTES, 'UTF-8')) ?>
                                                </td>
                                                <td>
                                                    <span class="badge <?= ($request['ai_status'] ?? '') === 'generated' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                                        <?= htmlspecialchars((string) ($request['ai_status'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="Views/assets/js/jquery-3.7.1.min.js"></script>
    <script src="Views/assets/js/bootstrap.min.js"></script>
    <script src="Views/assets/js/jquery.scrollbar.min.js"></script>
    <script src="Views/assets/js/kaiadmin.min.js"></script>
</body>
</html>
