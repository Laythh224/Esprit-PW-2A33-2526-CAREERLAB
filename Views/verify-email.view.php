<?php if (!defined('BASE_URL')) define('BASE_URL', '/mon_site/'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verification email</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/plugins.min.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/kaiadmin.min.css" />
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
            <h4 class="mb-0">Verification email</h4>
          </div>
          <div class="card-body">
            <p class="text-muted mb-3">Un code de verification a ete envoye a votre email.</p>

            <?php if (!empty($message)): ?>
              <div class="alert alert-<?= htmlspecialchars($messageType, ENT_QUOTES, 'UTF-8') ?>" role="alert">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
              </div>
            <?php endif; ?>

            <?php if ($email !== '' && $role !== '' && !$isVerified && $attemptsLeft > 0): ?>
              <form method="POST" novalidate data-auth-form="email-code" data-expire-seconds="<?= max(0, (int) $expiresInSeconds) ?>" data-resend-seconds="<?= (int) $resendWaitSeconds ?>">
                <div class="mb-3">
                  <label for="code" class="form-label">Code de verification</label>
                  <input type="text" class="form-control" id="code" name="code" maxlength="6" inputmode="numeric" placeholder="Entrez le code" />
                  <div class="small text-danger d-block" id="codeError"></div>
                  <div class="form-text">
                    Tentatives restantes : <?= (int) $attemptsLeft ?><br>
                    Expiration dans : <span id="emailVerificationCountdown"><?= max(0, (int) $expiresInSeconds) ?></span> secondes
                  </div>
                </div>
                <div class="d-grid gap-2">
                  <button type="submit" name="action" value="verify" class="btn btn-primary">Verifier</button>
                  <button type="submit" name="action" value="resend" class="btn btn-outline-primary" <?= (int) $resendWaitSeconds > 0 ? 'disabled' : '' ?>>Renvoyer le code</button>
                </div>
              </form>
            <?php elseif ($email !== '' && $role !== '' && !$isVerified): ?>
              <form method="POST" novalidate>
                <div class="d-grid">
                  <button type="submit" name="action" value="resend" class="btn btn-outline-primary">Renvoyer le code</button>
                </div>
              </form>
            <?php else: ?>
              <div class="d-grid">
                <a href="index.php?page=login" class="btn btn-primary">Se connecter</a>
              </div>
            <?php endif; ?>
          </div>
          <div class="card-footer text-center bg-white border-0 pt-0 pb-3">
            <a href="index.php?page=login">Retour a la connexion</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?= BASE_URL ?>Views/assets/js/auth-validation.js"></script>
</body>
</html>
