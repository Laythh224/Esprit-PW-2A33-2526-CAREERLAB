<?php
if (!defined('BASE_URL')) define('BASE_URL', '/mon_site/');
$state = $state ?? [
    'message' => '',
    'messageType' => 'info',
    'errors' => ['email' => ''],
    'emailValue' => '',
];
?>
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
                        <h4 class="mb-0">Réinitialiser votre mot de passe</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Saisissez votre adresse email. Si un compte existe avec cette adresse, 
                            vous recevrez un lien pour réinitialiser votre mot de passe.
                        </p>

                        <?php if ($state['message'] !== ''): ?>
                            <div class="alert alert-<?= htmlspecialchars($state['messageType'], ENT_QUOTES, 'UTF-8') ?>" role="alert">
                                <?= htmlspecialchars($state['message'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" novalidate>
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email</label>
                                <input 
                                    type="email" 
                                    class="form-control <?= $state['errors']['email'] !== '' ? 'is-invalid' : '' ?>" 
                                    id="email" 
                                    name="email" 
                                    value="<?= htmlspecialchars($state['emailValue'], ENT_QUOTES, 'UTF-8') ?>" 
                                    placeholder="votre@email.com"
                                    required
                                    autocomplete="email"
                                >
                                <?php if ($state['errors']['email'] !== ''): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= htmlspecialchars($state['errors']['email'], ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Envoyer le lien
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-white border-0 pt-0 pb-3">
                        <small class="text-muted">
                            <a href="index.php?page=login">Retour à la connexion</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
