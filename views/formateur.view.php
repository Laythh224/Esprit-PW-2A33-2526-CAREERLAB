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
  <title>Créer un compte Formateur</title>
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
                        <h4 class="mb-0">Inscription Formateur</h4>
                    </div>
                    <div class="card-body">
                        <form id="trainerSignupForm" method="POST" enctype="multipart/form-data" novalidate data-phone-form>
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input class="form-control <?php echo ($fieldErrors['nom'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="nom" name="nom" placeholder="Votre nom" value="<?php echo htmlspecialchars((string) ($old['nom'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="nomError"><?php echo htmlspecialchars((string) ($fieldErrors['nom'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input class="form-control <?php echo ($fieldErrors['prenom'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="prenom" name="prenom" placeholder="Votre prénom" value="<?php echo htmlspecialchars((string) ($old['prenom'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="prenomError"><?php echo htmlspecialchars((string) ($fieldErrors['prenom'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control <?php echo ($fieldErrors['email'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="votre@email.com" value="<?php echo htmlspecialchars((string) ($old['email'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="emailError"><?php echo htmlspecialchars((string) ($fieldErrors['email'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input class="form-control <?php echo ($fieldErrors['password'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="********" value="<?php echo htmlspecialchars((string) ($old['password'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="passwordError"><?php echo htmlspecialchars((string) ($fieldErrors['password'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                                <input class="form-control <?php echo ($fieldErrors['confirm_password'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="confirmPassword" name="confirm_password" placeholder="********" value="<?php echo htmlspecialchars((string) ($old['confirm_password'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="confirmPasswordError"><?php echo htmlspecialchars((string) ($fieldErrors['confirm_password'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <div class="mb-1">
                                  <label for="phone" class="form-label">Numéro de téléphone</label>
                                </div>
                                <div class="mb-3">
                                  <input id="phone" name="phone" class="form-control">
                                </div>
                                <input type="hidden" id="full_phone" name="full_phone">
                            </div>
                            <hr />
                            <h6 class="mb-3">Informations professionnelles</h6>
                            <div class="mb-3">
                                <label for="specialite" class="form-label">Spécialité</label>
                                <input type="text" class="form-control <?php echo ($fieldErrors['specialite'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="specialite" name="specialite" placeholder="Ex : Développement Web" value="<?php echo htmlspecialchars((string) ($old['specialite'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="specialiteError"><?php echo htmlspecialchars((string) ($fieldErrors['specialite'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="diplomeCount" class="form-label">Nombre de diplômes</label>
                                <input class="form-control <?php echo ($fieldErrors['diplomes'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="diplomeCount" name="diplome_count" placeholder="Ex : 2" value="<?php echo htmlspecialchars((string) ($old['diplome_count'] ?? '')); ?>" />
                                <div class="small text-danger d-block" id="diplomesError"><?php echo htmlspecialchars((string) ($fieldErrors['diplomes'] ?? '')); ?></div>
                            </div>
                            <div id="diplomeFiles" class="mb-3"></div>
                            <div class="mb-3">
                                <label for="experience" class="form-label">Expérience</label>
                                <textarea class="form-control <?php echo ($fieldErrors['experience'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="experience" name="experience" rows="3" placeholder="Ex : 3 ans d'enseignement"><?php echo htmlspecialchars((string) ($old['experience'] ?? '')); ?></textarea>
                                <div class="small text-danger d-block" id="experienceError"><?php echo htmlspecialchars((string) ($fieldErrors['experience'] ?? '')); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="cv" class="form-label">CV (PDF)</label>
                                <input class="form-control <?php echo ($fieldErrors['cv'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="cv" name="cv" accept="application/pdf,.pdf" />
                                <div class="small text-danger d-block" id="cvError"><?php echo htmlspecialchars((string) ($fieldErrors['cv'] ?? '')); ?></div>
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
        const form = document.getElementById("trainerSignupForm");
        const nomInput = document.getElementById("nom");
        const prenomInput = document.getElementById("prenom");
        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");
        const confirmPasswordInput = document.getElementById("confirmPassword");
        const telephoneInput = document.getElementById("telephone");
        const specialiteInput = document.getElementById("specialite");
        const diplomeCountInput = document.getElementById("diplomeCount");
        const experienceInput = document.getElementById("experience");
        const cvInput = document.getElementById("cv");
        const diplomeFilesContainer = document.getElementById("diplomeFiles");
        const message = document.getElementById("formMessage");
        const popupContainer = document.getElementById("validationPopups");
        const fieldErrors = {
          nom: document.getElementById("nomError"),
          prenom: document.getElementById("prenomError"),
          email: document.getElementById("emailError"),
          password: document.getElementById("passwordError"),
          confirmPassword: document.getElementById("confirmPasswordError"),
          telephone: document.getElementById("telephoneError"),
          specialite: document.getElementById("specialiteError"),
          diplomes: document.getElementById("diplomesError"),
          experience: document.getElementById("experienceError"),
          cv: document.getElementById("cvError"),
        };
        const fieldInputs = {
          nom: nomInput,
          prenom: prenomInput,
          email: emailInput,
          password: passwordInput,
          confirmPassword: confirmPasswordInput,
          telephone: telephoneInput,
          specialite: specialiteInput,
          diplomes: diplomeCountInput,
          experience: experienceInput,
          cv: cvInput,
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

        function renderDiplomeInputs(count) {
          diplomeFilesContainer.innerHTML = "";
          if (!Number.isInteger(count) || count <= 0) {
            return;
          }
          for (let i = 1; i <= count; i += 1) {
            const wrapper = document.createElement("div");
            wrapper.className = "mb-2";

            const label = document.createElement("label");
            label.className = "form-label";
            label.setAttribute("for", "diplome" + i);
            label.textContent = "Diplôme " + i + " (PDF)";

            const input = document.createElement("input");
            input.type = "file";
            input.className = "form-control";
            input.id = "diplome" + i;
            input.name = "diplome" + i;
            input.accept = "application/pdf,.pdf";

            wrapper.appendChild(label);
            wrapper.appendChild(input);
            diplomeFilesContainer.appendChild(wrapper);
          }
        }

        diplomeCountInput.addEventListener("input", function () {
          const count = Number.parseInt(diplomeCountInput.value, 10);
          renderDiplomeInputs(Number.isNaN(count) ? 0 : count);
        });

        renderDiplomeInputs(Number.parseInt(diplomeCountInput.value || "0", 10) || 0);

        form.addEventListener("submit", function (event) {
          clearFieldErrors();
          let hasError = false;

          if (!nomInput.value.trim()) { setFieldError("nom", "Le nom est obligatoire."); showPopup("Le nom est obligatoire."); hasError = true; }
          if (!prenomInput.value.trim()) { setFieldError("prenom", "Le prénom est obligatoire."); showPopup("Le prénom est obligatoire."); hasError = true; }
          if (!emailInput.value.trim()) { setFieldError("email", "L'email est obligatoire."); showPopup("L'email est obligatoire."); hasError = true; }
          else if (!isValidEmail(emailInput.value.trim())) { setFieldError("email", "Veuillez saisir une adresse email valide."); showPopup("Veuillez saisir une adresse email valide."); hasError = true; }
          if (!passwordInput.value) { setFieldError("password", "Le mot de passe est obligatoire."); showPopup("Le mot de passe est obligatoire."); hasError = true; }
          else if (passwordInput.value.length < 6) { setFieldError("password", "Le mot de passe doit contenir au moins 6 caractères."); showPopup("Le mot de passe doit contenir au moins 6 caractères."); hasError = true; }
          if (!confirmPasswordInput.value) { setFieldError("confirmPassword", "La confirmation du mot de passe est obligatoire."); showPopup("La confirmation du mot de passe est obligatoire."); hasError = true; }
          else if (passwordInput.value !== confirmPasswordInput.value) { setFieldError("confirmPassword", "La confirmation ne correspond pas au mot de passe."); showPopup("La confirmation ne correspond pas au mot de passe."); hasError = true; }
          if (!telephoneInput.value.trim()) { setFieldError("telephone", "Le téléphone est obligatoire."); showPopup("Le téléphone est obligatoire."); hasError = true; }
          else if (window.PhoneInputEnhancer) {
            const phoneValidation = window.PhoneInputEnhancer.validateField(form, true);
            if (!phoneValidation.isValid) {
              setFieldError("telephone", phoneValidation.message);
              showPopup(phoneValidation.message);
              hasError = true;
            }
          }
          if (!specialiteInput.value.trim()) { setFieldError("specialite", "La spécialité est obligatoire."); showPopup("La spécialité est obligatoire."); hasError = true; }

          const diplomeCount = Number.parseInt(diplomeCountInput.value || "0", 10);
          if (!Number.isInteger(diplomeCount) || diplomeCount <= 0) {
            setFieldError("diplomes", "Le nombre de diplômes est obligatoire.");
            showPopup("Le nombre de diplômes est obligatoire.");
            hasError = true;
          } else if (diplomeCount > 20) {
            setFieldError("diplomes", "Le nombre de diplômes est trop élevé.");
            showPopup("Le nombre de diplômes est trop élevé.");
            hasError = true;
          } else {
            for (let i = 1; i <= diplomeCount; i += 1) {
              const input = document.getElementById("diplome" + i);
              const file = input && input.files ? input.files[0] : null;
              if (!file) {
                setFieldError("diplomes", "Tous les diplômes sont obligatoires.");
                showPopup("Tous les diplômes sont obligatoires.");
                hasError = true;
                break;
              }
              if (!file.name.toLowerCase().endsWith(".pdf")) {
                setFieldError("diplomes", "Chaque diplôme doit être un PDF.");
                showPopup("Chaque diplôme doit être un PDF.");
                hasError = true;
                break;
              }
            }
          }

          if (!experienceInput.value.trim()) { setFieldError("experience", "L'expérience est obligatoire."); showPopup("L'expérience est obligatoire."); hasError = true; }

          const cvFile = cvInput.files[0];
          if (!cvFile) {
            setFieldError("cv", "Le CV est obligatoire.");
            showPopup("Le CV est obligatoire.");
            hasError = true;
          } else if (!cvFile.name.toLowerCase().endsWith(".pdf")) {
            setFieldError("cv", "Le CV doit être au format PDF.");
            showPopup("Le CV doit être au format PDF.");
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

