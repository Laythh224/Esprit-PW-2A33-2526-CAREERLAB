<!DOCTYPE html>
<html lang="fr">
  <?php if (!defined('BASE_URL')) define('BASE_URL', '/mon_site/'); ?>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/kaiadmin.min.css" />
  </head>
  <body>
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card shadow-lg border-0">
            <div class="card-body p-5 text-center">
              <h1 class="mb-3">Profil</h1>
              <p class="text-muted mb-4">Cette page est maintenant servie en PHP pour rester cohérente avec le reste du site.</p>
              <a href="index.php?r=admin&view=dashboard" class="btn btn-primary">Retour au tableau de bord</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

