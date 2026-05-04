<?php
if (!defined('BASE_URL')) define('BASE_URL', '/mon_site/');
$state = $state ?? [
    'message' => '',
    'messageType' => 'info',
    'errors' => [
        'password' => '',
        'confirm_password' => '',
    ],
    'token' => '',
];
?>
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
                        <p class="text-muted mb-3">
                            Créez un nouveau mot de passe sécurisé pour votre compte.
                        </p>

                        <!-- Afficher les erreurs globales -->
                        <?php if ($state['message'] !== ''): ?>
                            <div class="alert alert-<?= htmlspecialchars($state['messageType'], ENT_QUOTES, 'UTF-8') ?>" role="alert">
                                <?= htmlspecialchars($state['message'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <!-- Avertissement de sécurité -->
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <small>
                                <strong>🔒 Sécurité :</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Minimum 8 caractères</li>
                                    <li>1 majuscule (A-Z)</li>
                                    <li>1 minuscule (a-z)</li>
                                    <li>1 chiffre (0-9)</li>
                                    <li>1 caractère spécial (!@#$%^&* etc.)</li>
                                </ul>
                            </small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <form method="POST" novalidate id="passwordForm">
                            <!-- Token caché -->
                            <input type="hidden" name="token" value="<?= $state['token'] ?? '' ?>">

                            <!-- Mot de passe -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <div class="input-group">
                                    <input 
                                        type="password" 
                                        class="form-control <?= ($state['errors']['password'] ?? '') !== '' ? 'is-invalid' : '' ?>" 
                                        id="password" 
                                        name="password" 
                                        placeholder="••••••••"
                                        required
                                        minlength="8"
                                    >
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        👁️
                                    </button>
                                </div>
                                <?php if (($state['errors']['password'] ?? '') !== ''): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= htmlspecialchars((string) ($state['errors']['password'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                <?php endif; ?>
                                <!-- Indicateur de force -->
                                <small class="form-text text-muted" id="passwordStrength" style="display: none;">
                                    <span id="strengthBar" style="display: inline-block; width: 100px; height: 6px; background: #e9ecef; border-radius: 3px; margin: 0 8px;">
                                        <span id="strengthFill" style="display: block; height: 100%; border-radius: 3px; width: 0%; transition: all 0.3s; background: #dc3545;"></span>
                                    </span>
                                    <span id="strengthText"></span>
                                </small>
                            </div>

                            <!-- Confirmation mot de passe -->
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                                <div class="input-group">
                                    <input 
                                        type="password" 
                                        class="form-control <?= ($state['errors']['confirm_password'] ?? '') !== '' ? 'is-invalid' : '' ?>" 
                                        id="confirmPassword" 
                                        name="confirm_password" 
                                        placeholder="••••••••"
                                        required
                                        minlength="8"
                                    >
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirm">
                                        👁️
                                    </button>
                                </div>
                                <?php if (($state['errors']['confirm_password'] ?? '') !== ''): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= htmlspecialchars((string) ($state['errors']['confirm_password'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                <?php endif; ?>
                                <small class="form-text" id="matchIndicator" style="display: none;"></small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Enregistrer le nouveau mot de passe
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

    <script>
        // Afficher/masquer mot de passe
        document.getElementById('togglePassword').addEventListener('click', function() {
            const field = document.getElementById('password');
            field.type = field.type === 'password' ? 'text' : 'password';
        });

        document.getElementById('toggleConfirm').addEventListener('click', function() {
            const field = document.getElementById('confirmPassword');
            field.type = field.type === 'password' ? 'text' : 'password';
        });

        // Indicateur de force du mot de passe
        const passwordField = document.getElementById('password');
        passwordField.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            const strengthIndicator = document.getElementById('passwordStrength');

            if (password.length === 0) {
                strengthIndicator.style.display = 'none';
                return;
            }

            strengthIndicator.style.display = 'inline';

            let strength = 0;
            const checks = [
                password.length >= 8,
                /[a-z]/.test(password),
                /[A-Z]/.test(password),
                /[0-9]/.test(password),
                /[!@#$%^&*()_\-+=\[\]{};:\'",.<>?\/\\|`~]/.test(password)
            ];

            strength = checks.filter(Boolean).length;
            const percentage = (strength / 5) * 100;

            strengthFill.style.width = percentage + '%';

            if (strength === 1) {
                strengthFill.style.background = '#dc3545';
                strengthText.textContent = 'Très faible';
            } else if (strength === 2) {
                strengthFill.style.background = '#fd7e14';
                strengthText.textContent = 'Faible';
            } else if (strength === 3) {
                strengthFill.style.background = '#ffc107';
                strengthText.textContent = 'Moyen';
            } else if (strength === 4) {
                strengthFill.style.background = '#17a2b8';
                strengthText.textContent = 'Bon';
            } else if (strength === 5) {
                strengthFill.style.background = '#28a745';
                strengthText.textContent = 'Excellent';
            }
        });

        // Vérifier la correspondance des mots de passe
        const confirmField = document.getElementById('confirmPassword');
        const matchIndicator = document.getElementById('matchIndicator');

        [passwordField, confirmField].forEach(field => {
            field.addEventListener('input', function() {
                if (confirmField.value.length === 0) {
                    matchIndicator.style.display = 'none';
                    return;
                }

                matchIndicator.style.display = 'inline';
                if (passwordField.value === confirmField.value) {
                    matchIndicator.textContent = '✓ Les mots de passe correspondent';
                    matchIndicator.className = 'form-text text-success';
                } else {
                    matchIndicator.textContent = '✗ Les mots de passe ne correspondent pas';
                    matchIndicator.className = 'form-text text-danger';
                }
            });
        });
    </script>
</body>
</html>
