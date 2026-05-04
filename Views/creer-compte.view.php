<?php if (!defined('BASE_URL')) define('BASE_URL', '/mon_site/'); ?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Creer un compte</title>
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
              <h4 class="mb-0">Creer un compte</h4>
            </div>
            <div class="card-body">
              <p class="text-muted mb-4">
                Choisissez le type de compte que vous souhaitez creer.
              </p>
              <div class="d-grid gap-3 auth-choice-grid">
                <a href="index.php?page=utilisateur" class="btn btn-primary">
                  Utilisateur
                </a>
                <a href="index.php?page=formateur" class="btn btn-primary">
                  Formateur
                </a>
                <a href="index.php?page=entreprise" class="btn btn-primary">
                  Entreprise
                </a>
              </div>
            </div>
            <div class="card-footer text-center bg-white border-0 pt-0 pb-3">
              <a href="index.php?page=accueil" class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm border-0 mt-2 mb-2">&larr; Retour a l'accueil</a>
              <br />
              <a href="index.php?page=login">Deja un compte ? Se connecter</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
