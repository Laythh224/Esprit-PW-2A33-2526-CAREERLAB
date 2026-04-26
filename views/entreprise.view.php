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
  <title>Créer un compte Entreprise</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/plugins.min.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/kaiadmin.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css"/>
</head>
<body>
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
                        <h4 class="mb-0">Inscription Entreprise</h4>
                    </div>
                    <div class="card-body">
                        <form id="companySignupForm" method="POST" novalidate data-phone-form>
                            <div class="mb-3">
                                <label for="nomEntreprise" class="form-label">Nom de l'entreprise</label>
                                <input type="text" class="form-control <?php echo ($fieldErrors['nom'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="nomEntreprise" name="nom" placeholder="Nom de l'entreprise" value="<?php echo htmlspecialchars((string) ($old['nom'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="nomEntrepriseError"><?php echo htmlspecialchars((string) ($fieldErrors['nom'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control <?php echo ($fieldErrors['email'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="contact@entreprise.com" value="<?php echo htmlspecialchars((string) ($old['email'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="emailError"><?php echo htmlspecialchars((string) ($fieldErrors['email'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control <?php echo ($fieldErrors['password'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="********" value="<?php echo htmlspecialchars((string) ($old['password'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="passwordError"><?php echo htmlspecialchars((string) ($fieldErrors['password'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control <?php echo ($fieldErrors['confirm_password'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="confirmPassword" name="confirm_password" placeholder="********" value="<?php echo htmlspecialchars((string) ($old['confirm_password'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="confirmPasswordError"><?php echo htmlspecialchars((string) ($fieldErrors['confirm_password'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <div class="mb-1">
                                  <label for="phone" class="form-label">Numéro de téléphone</label>
                                     <!-- Champ CV supprimé -->
                                <div class="mb-3">
                                  <input id="phone" name="phone" class="form-control">
                                </div>
                                <input type="hidden" id="full_phone" name="full_phone">
                            </div>
                            <div class="mb-3">
                                <label for="codeFiscal" class="form-label">Code fiscal</label>
                                <input type="text" class="form-control <?php echo ($fieldErrors['code_fiscal'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="codeFiscal" name="code_fiscal" placeholder="11 chiffres" value="<?php echo htmlspecialchars((string) ($old['code_fiscal'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="codeFiscalError"><?php echo htmlspecialchars((string) ($fieldErrors['code_fiscal'] ?? '')); ?></div>
                            </div>
                            <hr />
                            <h6 class="mb-3">Informations professionnelles</h6>
                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <input type="text" class="form-control <?php echo ($fieldErrors['adresse'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="adresse" name="adresse" placeholder="Adresse complète" value="<?php echo htmlspecialchars((string) ($old['adresse'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="adresseError"><?php echo htmlspecialchars((string) ($fieldErrors['adresse'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="ville" class="form-label">Ville</label>
                                <input type="text" class="form-control <?php echo ($fieldErrors['ville'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="ville" name="ville" placeholder="Ville" value="<?php echo htmlspecialchars((string) ($old['ville'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="villeError"><?php echo htmlspecialchars((string) ($fieldErrors['ville'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="secteur" class="form-label">Secteur d'activité</label>
                                <select class="form-select <?php echo ($fieldErrors['secteur'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="secteur" name="secteur">
                                    <option value="" <?php echo ($old['secteur'] ?? '') === '' ? 'selected' : ''; ?> disabled>Choisissez un secteur</option>
                                    <option value="Informatique" <?php echo ($old['secteur'] ?? '') === 'Informatique' ? 'selected' : ''; ?>>Informatique</option>
                                    <option value="Finance" <?php echo ($old['secteur'] ?? '') === 'Finance' ? 'selected' : ''; ?>>Finance</option>
                                    <option value="Industrie" <?php echo ($old['secteur'] ?? '') === 'Industrie' ? 'selected' : ''; ?>>Industrie</option>
                                    <option value="Autre" <?php echo ($old['secteur'] ?? '') === 'Autre' ? 'selected' : ''; ?>>Autre</option>
                                </select>
                                <div class="small text-danger d-block" id="secteurError"><?php echo htmlspecialchars((string) ($fieldErrors['secteur'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control <?php echo ($fieldErrors['description'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="description" name="description" rows="3" placeholder="Présentation de l'entreprise"><?php echo htmlspecialchars((string) ($old['description'] ?? '')); ?></textarea>
                                <div class="small text-danger d-block" id="descriptionError"><?php echo htmlspecialchars((string) ($fieldErrors['description'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="siteWeb" class="form-label">Site web (optionnel)</label>
                                <input type="text" class="form-control <?php echo ($fieldErrors['site'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="siteWeb" name="site" placeholder="https://votre-site.com" value="<?php echo htmlspecialchars((string) ($old['site'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="siteWebError"><?php echo htmlspecialchars((string) ($fieldErrors['site'] ?? '')); ?></div>
                            </div>
                            <div id="validationPopups" class="position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>
                            <div id="formMessage" class="alert d-none" role="alert"></div>
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
      const input = document.querySelector("#phone");
      const hiddenInput = document.querySelector("#full_phone");
      const iti = window.intlTelInput(input, {
        initialCountry: "tn",
        preferredCountries: ["tn", "fr", "us"],
        separateDialCode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
      });
      input.addEventListener('input', function() {
        hiddenInput.value = iti.getNumber();
      });
      input.addEventListener('countrychange', function() {
        hiddenInput.value = iti.getNumber();
      });
    </script>
    <script>
      (function () {
        const form = document.getElementById("companySignupForm");
        const nomEntrepriseInput = document.getElementById("nomEntreprise");
        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");
        const confirmPasswordInput = document.getElementById("confirmPassword");
        const telephoneInput = document.getElementById("telephone");
        const codeFiscalInput = document.getElementById("codeFiscal");
        const adresseInput = document.getElementById("adresse");
        const villeInput = document.getElementById("ville");
        const secteurInput = document.getElementById("secteur");
        const descriptionInput = document.getElementById("description");
        const siteWebInput = document.getElementById("siteWeb");
        const message = document.getElementById("formMessage");
        const popupContainer = document.getElementById("validationPopups");
        const fieldErrors = {
          nomEntreprise: document.getElementById("nomEntrepriseError"),
          email: document.getElementById("emailError"),
          password: document.getElementById("passwordError"),
          confirmPassword: document.getElementById("confirmPasswordError"),
          telephone: document.getElementById("telephoneError"),
          codeFiscal: document.getElementById("codeFiscalError"),
          adresse: document.getElementById("adresseError"),
          ville: document.getElementById("villeError"),
          secteur: document.getElementById("secteurError"),
          description: document.getElementById("descriptionError"),
          siteWeb: document.getElementById("siteWebError"),
        };
        const fieldInputs = {
          nomEntreprise: nomEntrepriseInput,
          email: emailInput,
          password: passwordInput,
          confirmPassword: confirmPasswordInput,
          telephone: telephoneInput,
          codeFiscal: codeFiscalInput,
          adresse: adresseInput,
          ville: villeInput,
          secteur: secteurInput,
          description: descriptionInput,
          siteWeb: siteWebInput,
        };

        function clearFieldErrors() {
          Object.entries(fieldErrors).forEach(([key, element]) => {
            if (element) {
              element.textContent = "";
            }
            const input = fieldInputs[key];
            if (input) {
              input.classList.remove("is-invalid");
            }
          });
        }

        function setFieldError(key, text) {
          const error = fieldErrors[key];
          const input = fieldInputs[key];
          if (error) {
            error.textContent = text;
          }
          if (input) {
            input.classList.add("is-invalid");
          }
        }

        function isValidEmail(email) {
          return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function showPopup(text) {
          if (!popupContainer) {
            return;
          }
          const popup = document.createElement("div");
          popup.className = "alert alert-warning shadow-sm mb-2";
          popup.textContent = text;
          popupContainer.appendChild(popup);
          setTimeout(function () {
            popup.remove();
          }, 2500);
        }

        function showMessage(text, type) {
          message.className = "alert alert-" + type;
          message.textContent = text;
          message.classList.remove("d-none");
        }

        form.addEventListener("submit", function (event) {
          clearFieldErrors();
          let hasError = false;

          if (!nomEntrepriseInput.value.trim()) { setFieldError("nomEntreprise", "Le nom de l'entreprise est obligatoire."); showPopup("Le nom de l'entreprise est obligatoire."); hasError = true; }
          if (!emailInput.value.trim()) { setFieldError("email", "L'email est obligatoire."); showPopup("L'email est obligatoire."); hasError = true; }
          else if (!isValidEmail(emailInput.value.trim())) { setFieldError("email", "Veuillez saisir une adresse email valide."); showPopup("Veuillez saisir une adresse email valide."); hasError = true; }
          if (!passwordInput.value) { setFieldError("password", "Le mot de passe est obligatoire."); showPopup("Le mot de passe est obligatoire."); hasError = true; }
          if (!confirmPasswordInput.value) { setFieldError("confirmPassword", "La confirmation du mot de passe est obligatoire."); showPopup("La confirmation du mot de passe est obligatoire."); hasError = true; }
          if (!telephoneInput.value.trim()) { setFieldError("telephone", "Le téléphone est obligatoire."); showPopup("Le téléphone est obligatoire."); hasError = true; }
          else if (window.PhoneInputEnhancer) {
            const phoneValidation = window.PhoneInputEnhancer.validateField(form, true);
            if (!phoneValidation.isValid) {
              setFieldError("telephone", phoneValidation.message);
              showPopup(phoneValidation.message);
              hasError = true;
            }
          }
          if (!codeFiscalInput.value.trim()) { setFieldError("codeFiscal", "Le code fiscal est obligatoire."); showPopup("Le code fiscal est obligatoire."); hasError = true; }
          if (!adresseInput.value.trim()) { setFieldError("adresse", "L'adresse est obligatoire."); showPopup("L'adresse est obligatoire."); hasError = true; }
          if (!villeInput.value.trim()) { setFieldError("ville", "La ville est obligatoire."); showPopup("La ville est obligatoire."); hasError = true; }
          if (!secteurInput.value.trim()) { setFieldError("secteur", "Le secteur d'activité est obligatoire."); showPopup("Le secteur d'activité est obligatoire."); hasError = true; }
          if (!descriptionInput.value.trim()) { setFieldError("description", "La description est obligatoire."); showPopup("La description est obligatoire."); hasError = true; }

          if (codeFiscalInput.value.trim() && !/^\d{11}$/.test(codeFiscalInput.value.trim())) {
            setFieldError("codeFiscal", "Le code fiscal doit contenir 11 chiffres.");
            showPopup("Le code fiscal doit contenir 11 chiffres.");
            hasError = true;
          }

          if (passwordInput.value && passwordInput.value.length < 6) {
            setFieldError("password", "Le mot de passe doit contenir au moins 6 caractères.");
            showPopup("Le mot de passe doit contenir au moins 6 caractères.");
            hasError = true;
          }

          if (passwordInput.value && confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
            setFieldError("confirmPassword", "La confirmation ne correspond pas au mot de passe.");
            showPopup("La confirmation ne correspond pas au mot de passe.");
            hasError = true;
          }

          if (siteWebInput.value.trim() && !/^https?:\/\//i.test(siteWebInput.value.trim())) {
            setFieldError("siteWeb", "Le site web doit commencer par http:// ou https://.");
            showPopup("Le site web doit commencer par http:// ou https://.");
            hasError = true;
          }

          if (hasError) {
            event.preventDefault();
            showMessage("Les champs en rouge doivent être corrigés.", "danger");
            return;
          }

          showMessage("Inscription en cours...", "success");
        });
      })();
    </script>
</body>
</html>

