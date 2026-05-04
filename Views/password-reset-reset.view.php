<?php if (!defined('BASE_URL')) define('BASE_URL', '/mon_site/'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe</title>
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
                        <h4 class="mb-0">Définir un nouveau mot de passe</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($state['message'] !== ''): ?>
                            <div class="alert alert-<?= htmlspecialchars($state['messageType'], ENT_QUOTES, 'UTF-8') ?>" role="alert">
                                <?= htmlspecialchars($state['message'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" id="passwordResetForm" novalidate data-password-reset-form="reset">
                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control <?= $state['errors']['password'] !== '' ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="********">
                                <div class="small text-danger d-block" id="passwordError"><?= htmlspecialchars($state['errors']['password'], ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control <?= $state['errors']['confirm_password'] !== '' ? 'is-invalid' : '' ?>" id="confirmPassword" name="confirm_password" placeholder="********">
                                <div class="small text-danger d-block" id="confirmPasswordError"><?= htmlspecialchars($state['errors']['confirm_password'], ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Enregistrer le nouveau mot de passe</button>
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
