<?php
if (!defined('BASE_URL')) define('BASE_URL', '/mon_site/');
$message = $message ?? '';
$messageType = $messageType ?? 'info';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email envoyé</title>
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
                    <div class="card-body text-center py-5">
                        <!-- Icône de succès -->
                        <div style="font-size: 48px; color: #10b981; margin-bottom: 16px;">
                            ✓
                        </div>

                        <h3 class="card-title mb-3">Email envoyé !</h3>

                        <?php if ($message !== ''): ?>
                            <div class="alert alert-<?= htmlspecialchars($messageType, ENT_QUOTES, 'UTF-8') ?>" role="alert">
                                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <p class="text-muted mb-4">
                            Consultez votre boîte email. Si l'adresse existe dans nos registres, 
                            vous recevrez un lien pour réinitialiser votre mot de passe.
                        </p>

                        <div class="alert alert-info" role="alert">
                            <small>
                                <strong>💡 Conseil :</strong> Vérifiez aussi votre dossier spam/indésirables.
                                <br />
                                Le lien expire dans 15 minutes.
                            </small>
                        </div>

                        <div class="mt-4 d-flex gap-2 justify-content-center">
                            <a href="index.php?page=reset-password-request" class="btn btn-outline-primary">
                                Renvoyer un lien
                            </a>
                            <a href="index.php?page=login" class="btn btn-primary">
                                Retour à la connexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
