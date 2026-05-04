<?php if (!defined('BASE_URL')) define('BASE_URL', '/mon_site/'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérifier le code</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/plugins.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/kaiadmin.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/form-auth-ui.css">
</head>
<body class="auth-page">
    <div class="container py-5">
        <div class="row justify-content-center mb-4">
            <div class="col-auto text-center">
                <a href="index.php?page=accueil">
                    <img src="<?= BASE_URL ?>Views/assets/img/image_2026-04-11_005109464-removebg-preview.png" alt="CareerLab" style="height: 56px; max-width: 100%;" />
                </a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Vérification du code</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Un code a été envoyé à votre email. Entrez-le pour continuer.</p>

                        <?php if ($message !== ''): ?>
                            <div class="alert alert-<?= htmlspecialchars($messageType, ENT_QUOTES, 'UTF-8') ?>" role="alert">
                                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($status !== null): ?>
                            <form method="POST" id="passwordResetVerifyForm" novalidate data-password-reset-form="verify" data-expire-seconds="<?= (int) $status['expires_in'] ?>">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Code de vérification</label>
                                    <input type="text" class="form-control" id="code" name="code" maxlength="6" inputmode="numeric" placeholder="Entrez le code">
                                    <div class="small text-danger d-block" id="codeError"></div>
                                    <div class="form-text">
                                        Tentatives restantes : <?= (int) $status['attempts_left'] ?><br>
                                        Expiration dans : <span id="resetCountdown"><?= (int) $status['expires_in'] ?></span> secondes
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" name="action" value="verify" class="btn btn-primary">Vérifier</button>
                                    <button type="submit" name="action" value="resend" class="btn btn-outline-primary" <?= (int) $status['resend_wait'] > 0 ? 'disabled' : '' ?>>
                                        Renvoyer le code<?= (int) $status['resend_wait'] > 0 ? ' (' . (int) $status['resend_wait'] . 's)' : '' ?>
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="d-grid">
                                <a href="index.php?page=reset-password-request" class="btn btn-primary">Recommencer</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer text-center bg-white border-0 pt-0 pb-3">
                        <a href="index.php?page=login">Retour à la connexion</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= BASE_URL ?>Views/assets/js/password-reset.js"></script>
</body>
</html>
