<?php if (!defined('BASE_URL')) define('BASE_URL', '/mon_site/'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
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
                        <h4 class="mb-0">Mot de passe oublié</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Saisissez votre email pour recevoir un code de reinitialisation.</p>

                        <?php if ($state['message'] !== ''): ?>
                            <div class="alert alert-<?= htmlspecialchars($state['messageType'], ENT_QUOTES, 'UTF-8') ?>" role="alert">
                                <?= htmlspecialchars($state['message'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" id="passwordResetRequestForm" novalidate data-password-reset-form="request">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?= $state['errors']['email'] !== '' ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= htmlspecialchars($state['emailValue'], ENT_QUOTES, 'UTF-8') ?>" placeholder="votre@email.com">
                                <div class="small text-danger d-block" id="emailError"><?= htmlspecialchars($state['errors']['email'], ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Envoyer le code</button>
                            </div>
                        </form>
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
