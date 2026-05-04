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
  <title>Creer un compte Formateur</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/plugins.min.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/kaiadmin.min.css" />
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
                        <h4 class="mb-0">Inscription Formateur</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($serverError)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($serverError, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>

                        <form id="trainerSignupForm" method="POST" enctype="multipart/form-data" novalidate data-signup-form data-form-type="formateur">
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
                            <div class="mb-3 phone-field">
                                <label for="telephone" class="form-label">Numero de telephone</label>
                                <input type="tel" id="telephone" name="telephone_display" class="form-control <?= ($fieldErrors['telephone'] ?? '') !== '' ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars((string) ($old['telephone'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="+216 98 123 456" autocomplete="tel" inputmode="tel" data-phone-visible>
                                <input type="hidden" id="telephoneFull" name="telephone" value="<?= htmlspecialchars((string) ($old['telephone'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" data-phone-hidden>
                                <small class="phone-hint">Saisissez votre numero avec l'indicatif pays.</small>
                                <div class="small text-danger d-block" id="telephoneError"><?= htmlspecialchars((string) ($fieldErrors['telephone'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <hr />
                            <h6 class="mb-3">Informations professionnelles</h6>
                            <div class="mb-3">
                                <label for="specialite" class="form-label">Specialite</label>
                                <input type="text" class="form-control <?= ($fieldErrors['specialite'] ?? '') !== '' ? 'is-invalid' : '' ?>" id="specialite" name="specialite" placeholder="Ex : Developpement Web" value="<?= htmlspecialchars((string) ($old['specialite'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
                                <div class="small text-danger d-block" id="specialiteError"><?= htmlspecialchars((string) ($fieldErrors['specialite'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="diplomeCount" class="form-label">Nombre de diplomes</label>
                                <input type="number" min="1" step="1" class="form-control <?= ($fieldErrors['diplomes'] ?? '') !== '' ? 'is-invalid' : '' ?>" id="diplomeCount" name="diplome_count" placeholder="Ex : 2" value="<?= htmlspecialchars((string) ($old['diplome_count'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
                                <div class="small text-danger d-block" id="diplomesError"><?= htmlspecialchars((string) ($fieldErrors['diplomes'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div id="diplomeUploads" class="mb-3"></div>
                            <div class="mb-3">
                                <label for="experience" class="form-label">Experience</label>
                                <textarea class="form-control <?= ($fieldErrors['experience'] ?? '') !== '' ? 'is-invalid' : '' ?>" id="experience" name="experience" rows="3" placeholder="Ex : 3 ans"><?= htmlspecialchars((string) ($old['experience'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                                <div class="small text-danger d-block" id="experienceError"><?= htmlspecialchars((string) ($fieldErrors['experience'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="mb-3">
                              <label for="cv" class="form-label">Telecharger votre CV (PDF uniquement)</label>
                              <input class="form-control <?= ($fieldErrors['cv'] ?? '') !== '' ? 'is-invalid' : '' ?>" type="file" id="cv" name="cv" accept="application/pdf,.pdf" />
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
    <script>
      (function () {
        const countInput = document.getElementById("diplomeCount");
        const container = document.getElementById("diplomeUploads");

        if (!countInput || !container) {
          return;
        }

        function renderDiplomaInputs() {
          const count = Number.parseInt(countInput.value || "0", 10);
          container.innerHTML = "";

          if (!Number.isInteger(count) || count <= 0) {
            return;
          }

          for (let index = 1; index <= count; index += 1) {
            const wrapper = document.createElement("div");
            wrapper.className = "mb-3";

            const label = document.createElement("label");
            label.className = "form-label";
            label.setAttribute("for", "diplomeFile" + index);
            label.textContent = "Diplome " + index + " (PDF)";

            const input = document.createElement("input");
            input.type = "file";
            input.className = "form-control";
            input.id = "diplomeFile" + index;
            input.name = "diplome_files[]";
            input.accept = "application/pdf,.pdf";

            wrapper.appendChild(label);
            wrapper.appendChild(input);
            container.appendChild(wrapper);
          }
        }

        countInput.addEventListener("input", renderDiplomaInputs);
        renderDiplomaInputs();
      })();
    </script>
    <script src="<?= BASE_URL ?>Views/assets/js/signup-validation.js"></script>
</body>
</html>
