<?php
$old = $old ?? [];
$fieldErrors = $fieldErrors ?? [];
if (!defined('BASE_URL')) define('BASE_URL', '/mon_site/');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creer un compte Utilisateur</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/plugins.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/kaiadmin.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/form-auth-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css"/>
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
                        <h4 class="mb-0">Inscription Utilisateur</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($serverError)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($serverError, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data" novalidate data-signup-form data-form-type="utilisateur">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input class="form-control <?= ($fieldErrors['nom'] ?? '') !== '' ? 'is-invalid' : '' ?>" id="nom" name="nom" placeholder="Votre nom" value="<?= htmlspecialchars((string) ($old['nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
                                <div class="small text-danger d-block" id="nomError"><?= htmlspecialchars((string) ($fieldErrors['nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prenom</label>
                                <input class="form-control <?= ($fieldErrors['prenom'] ?? '') !== '' ? 'is-invalid' : '' ?>" id="prenom" name="prenom" placeholder="Votre prenom" value="<?= htmlspecialchars((string) ($old['prenom'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
                                <div class="small text-danger d-block" id="prenomError"><?= htmlspecialchars((string) ($fieldErrors['prenom'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?= ($fieldErrors['email'] ?? '') !== '' ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="votre@email.com" value="<?= htmlspecialchars((string) ($old['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
                                <div class="small text-danger d-block" id="emailError"><?= htmlspecialchars((string) ($fieldErrors['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="mb-3 phone-field">
                                <label for="telephone" class="form-label">Numero de telephone</label>
                                <input type="tel" id="telephone" name="telephone_display" class="form-control <?= ($fieldErrors['telephone'] ?? '') !== '' ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars((string) ($old['telephone'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="+216 98 123 456" autocomplete="tel" inputmode="tel" data-phone-visible>
                                <input type="hidden" id="telephoneFull" name="telephone" value="<?= htmlspecialchars((string) ($old['telephone'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" data-phone-hidden>
                                <small class="phone-hint">Saisissez votre numero avec l'indicatif pays.</small>
                                <div class="small text-danger d-block" id="telephoneError"><?= htmlspecialchars((string) ($fieldErrors['telephone'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control <?= ($fieldErrors['password'] ?? '') !== '' ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="********" value="<?= htmlspecialchars((string) ($old['password'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
                                <div class="small text-danger d-block" id="passwordError"><?= htmlspecialchars((string) ($fieldErrors['password'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control <?= ($fieldErrors['confirm_password'] ?? '') !== '' ? 'is-invalid' : '' ?>" id="confirmPassword" name="confirm_password" placeholder="********" value="<?= htmlspecialchars((string) ($old['confirm_password'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
                                <div class="small text-danger d-block" id="confirmPasswordError"><?= htmlspecialchars((string) ($fieldErrors['confirm_password'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <hr />
                            <h6 class="mb-3">Informations academiques / professionnelles</h6>
                            <div class="mb-3">
                                <label for="niveauEtude" class="form-label">Niveau d'etude</label>
                                <select class="form-select <?= ($fieldErrors['niveau'] ?? '') !== '' ? 'is-invalid' : '' ?>" id="niveauEtude" name="niveau">
                                    <option value="" <?= ($old['niveau'] ?? '') === '' ? 'selected' : '' ?> disabled>Choisissez un niveau</option>
                                    <option value="Bac" <?= ($old['niveau'] ?? '') === 'Bac' ? 'selected' : '' ?>>Bac</option>
                                    <option value="Licence" <?= ($old['niveau'] ?? '') === 'Licence' ? 'selected' : '' ?>>Licence</option>
                                    <option value="Master" <?= ($old['niveau'] ?? '') === 'Master' ? 'selected' : '' ?>>Master</option>
                                    <option value="Ingenieur" <?= ($old['niveau'] ?? '') === 'Ingenieur' ? 'selected' : '' ?>>Ingenieur</option>
                                </select>
                                <div class="small text-danger d-block" id="niveauEtudeError"><?= htmlspecialchars((string) ($fieldErrors['niveau'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="domaine" class="form-label">Domaine</label>
                                <select class="form-select <?= ($fieldErrors['domaine'] ?? '') !== '' ? 'is-invalid' : '' ?>" id="domaine" name="domaine">
                                    <option value="" <?= ($old['domaine'] ?? '') === '' ? 'selected' : '' ?> disabled>Choisissez un domaine</option>
                                    <option value="Informatique" <?= ($old['domaine'] ?? '') === 'Informatique' ? 'selected' : '' ?>>Informatique</option>
                                    <option value="Marketing" <?= ($old['domaine'] ?? '') === 'Marketing' ? 'selected' : '' ?>>Marketing</option>
                                    <option value="Finance" <?= ($old['domaine'] ?? '') === 'Finance' ? 'selected' : '' ?>>Finance</option>
                                    <option value="Ressources humaines" <?= ($old['domaine'] ?? '') === 'Ressources humaines' ? 'selected' : '' ?>>Ressources humaines</option>
                                    <option value="Autre" <?= ($old['domaine'] ?? '') === 'Autre' ? 'selected' : '' ?>>Autre</option>
                                </select>
                                <div class="small text-danger d-block" id="domaineError"><?= htmlspecialchars((string) ($fieldErrors['domaine'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="competences" class="form-label">Competences</label>
                                <textarea class="form-control" id="competences" name="competences" rows="3" placeholder="Ex : C, Java, HTML"><?= htmlspecialchars((string) ($old['competences'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="cv" class="form-label">CV (PDF)</label>
                                <input type="file" class="form-control <?= ($fieldErrors['cv'] ?? '') !== '' ? 'is-invalid' : '' ?>" id="cv" name="cv" accept="application/pdf,.pdf" />
                                <div class="small text-danger d-block" id="cvError"><?= htmlspecialchars((string) ($fieldErrors['cv'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="submit" class="btn btn-primary">S'inscrire</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-white border-0 pt-0 pb-3">
                        <a href="index.php?page=accueil" class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm border-0 mt-2 mb-2">&larr; Retour a l'accueil</a>
                        <br />
                        <a href="index.php?page=creer-compte">Retour au choix du type de compte</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>
    <script>
      (function () {
        const input = document.querySelector("#telephone");
        if (!input || !window.intlTelInput) {
          return;
        }

        window.intlTelInput(input, {
          initialCountry: "tn",
          preferredCountries: ["tn", "fr", "us"],
          utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
        });
      })();
    </script>
    <script src="<?= BASE_URL ?>Views/assets/js/signup-validation.js"></script>
</body>
</html>
