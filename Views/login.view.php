<!DOCTYPE html>
<html lang="fr">
<?php if (!defined('BASE_URL')) define('BASE_URL', '/mon_site/'); ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
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
                        <h4 class="mb-0">Connexion</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($_SESSION['login_flash']) && is_array($_SESSION['login_flash'])): ?>
                            <div class="alert alert-<?= htmlspecialchars((string) ($_SESSION['login_flash']['type'] ?? 'info'), ENT_QUOTES, 'UTF-8') ?>" role="alert">
                                <?= htmlspecialchars((string) ($_SESSION['login_flash']['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                            </div>
                            <?php unset($_SESSION['login_flash']); ?>
                        <?php endif; ?>
                        <?php if ($message !== ''): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>

                        <form id="loginForm" method="POST" novalidate data-auth-form="login">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control <?php echo $errors['email'] !== '' ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="votre@email.com" value="<?php echo htmlspecialchars($emailValue); ?>">
                                <div class="small text-danger d-block" id="emailError"><?php echo htmlspecialchars($errors['email']); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control <?php echo $errors['password'] !== '' ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="********">
                                <div class="small text-danger d-block" id="passwordError"><?php echo htmlspecialchars($errors['password']); ?></div>
                            </div>
                            <div id="validationPopups" class="position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>
                            <?php if (!empty($requireCaptcha) && $recaptchaSiteKey !== ''): ?>
                                <div class="mb-3">
                                    <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars($recaptchaSiteKey, ENT_QUOTES, 'UTF-8') ?>"></div>
                                    <div class="small text-danger d-block">
                                        <?php echo htmlspecialchars($errors['captcha']); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="d-grid">
                                <button type="submit" name="login" class="btn btn-primary">Se connecter</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <a href="index.php?page=reset-password-request">Mot de passe oublié ?</a>
                        </div>
                    </div>
                    <div class="card-footer text-center bg-white border-0 pt-0 pb-3">
                        <a href="index.php?page=accueil" class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm border-0 mt-2 mb-2">&larr; Retour à l'accueil</a>
                        <br />
                        <a href="index.php?page=creer-compte">Créer un compte</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= BASE_URL ?>Views/assets/js/auth-validation.js"></script>
    <?php if (!empty($requireCaptcha) && $recaptchaSiteKey !== ''): ?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
</body>
</html>

