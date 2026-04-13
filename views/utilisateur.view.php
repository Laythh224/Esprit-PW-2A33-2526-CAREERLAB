
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte Utilisateur</title>
    <link rel="stylesheet" href="views/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="views/assets/css/plugins.min.css">
    <link rel="stylesheet" href="views/assets/css/kaiadmin.min.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center mb-4">
            <div class="col-auto text-center">
                <a href="index.php?page=accueil">
                    <img src="views/assets/img/image_2026-04-11_005109464-removebg-preview.png" alt="CareerLab" style="height: 56px; max-width: 100%;" />
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
                        <?php if ($serverError !== ''): ?>
                            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($serverError); ?></div>
                        <?php endif; ?>
                        <form method="POST" enctype="multipart/form-data" novalidate>
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom" required />
                                <div class="small text-danger d-block" id="nomError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Votre prénom" required />
                                <div class="small text-danger d-block" id="prenomError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="votre@email.com" required />
                                <div class="small text-danger d-block" id="emailError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Votre téléphone" required />
                                <div class="small text-danger d-block" id="telephoneError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="********" required />
                                <div class="small text-danger d-block" id="passwordError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="confirmPassword" placeholder="********" required />
                                <div class="small text-danger d-block" id="confirmPasswordError"></div>
                            </div>
                            <hr />
                            <h6 class="mb-3">Informations académiques / professionnelles</h6>
                            <div class="mb-3">
                                <label for="niveauEtude" class="form-label">Niveau d'étude</label>
                                <select class="form-select" id="niveauEtude" name="niveau" required>
                                    <option value="" selected disabled>Choisissez un niveau</option>
                                    <option value="Bac">Bac</option>
                                    <option value="Licence">Licence</option>
                                    <option value="Master">Master</option>
                                    <option value="Ingénieur">Ingénieur</option>
                                </select>
                                <div class="small text-danger d-block" id="niveauEtudeError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="domaine" class="form-label">Domaine</label>
                                <select class="form-select" id="domaine" name="domaine" required>
                                    <option value="" selected disabled>Choisissez un domaine</option>
                                    <option value="Informatique">Informatique</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Ressources humaines">Ressources humaines</option>
                                    <option value="Autre">Autre</option>
                                </select>
                                <div class="small text-danger d-block" id="domaineError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="competences" class="form-label">Compétences</label>
                                <textarea class="form-control" id="competences" name="competences" rows="3" placeholder="Ex: C, Java, HTML"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="cv" class="form-label">CV (PDF)</label>
                                <input type="file" class="form-control" id="cv" name="cv" accept="application/pdf,.pdf" required />
                                <div class="small text-danger d-block" id="cvError"></div>
                            </div>
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
    <script>
      (function () {
        const form = document.querySelector("form");
                const nomInput = document.getElementById("nom");
                const prenomInput = document.getElementById("prenom");
                const emailInput = document.getElementById("email");
                const telephoneInput = document.getElementById("telephone");
        const passwordInput = document.getElementById("password");
        const confirmPasswordInput = document.getElementById("confirmPassword");
                const niveauEtudeInput = document.getElementById("niveauEtude");
                const domaineInput = document.getElementById("domaine");
        const cvInput = document.getElementById("cv");
        const message = document.getElementById("formMessage");
                const fieldErrors = {
                    nom: document.getElementById("nomError"),
                    prenom: document.getElementById("prenomError"),
                    email: document.getElementById("emailError"),
                    telephone: document.getElementById("telephoneError"),
                    password: document.getElementById("passwordError"),
                    confirmPassword: document.getElementById("confirmPasswordError"),
                    niveauEtude: document.getElementById("niveauEtudeError"),
                    domaine: document.getElementById("domaineError"),
                    cv: document.getElementById("cvError"),
                };
                const fieldInputs = {
                    nom: nomInput,
                    prenom: prenomInput,
                    email: emailInput,
                    telephone: telephoneInput,
                    password: passwordInput,
                    confirmPassword: confirmPasswordInput,
                    niveauEtude: niveauEtudeInput,
                    domaine: domaineInput,
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

        function showMessage(text, type) {
          message.className = "alert alert-" + type;
          message.textContent = text;
          message.classList.remove("d-none");
        }

        form.addEventListener("submit", function (event) {
                    clearFieldErrors();

                    let hasError = false;

                    if (!nomInput.value.trim()) { setFieldError("nom", "Le nom est obligatoire."); hasError = true; }
                    if (!prenomInput.value.trim()) { setFieldError("prenom", "Le prénom est obligatoire."); hasError = true; }
                    if (!emailInput.value.trim()) { setFieldError("email", "L'email est obligatoire."); hasError = true; }
                    else if (!emailInput.checkValidity()) { setFieldError("email", "Veuillez saisir une adresse email valide."); hasError = true; }
                    if (!telephoneInput.value.trim()) { setFieldError("telephone", "Le téléphone est obligatoire."); hasError = true; }
                    if (!passwordInput.value) { setFieldError("password", "Le mot de passe est obligatoire."); hasError = true; }
                    if (!confirmPasswordInput.value) { setFieldError("confirmPassword", "La confirmation du mot de passe est obligatoire."); hasError = true; }
                    if (!niveauEtudeInput.value) { setFieldError("niveauEtude", "Le niveau d'étude est obligatoire."); hasError = true; }
                    if (!domaineInput.value) { setFieldError("domaine", "Le domaine est obligatoire."); hasError = true; }

                    const cvFile = cvInput.files[0];
                    if (!cvFile) {
                        setFieldError("cv", "Le CV est obligatoire.");
                        hasError = true;
                    } else if (!cvFile.name.toLowerCase().endsWith(".pdf")) {
                        setFieldError("cv", "Le CV doit être au format PDF.");
                        hasError = true;
                    }

                    if (passwordInput.value && confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                        setFieldError("confirmPassword", "Le mot de passe et la confirmation doivent être identiques.");
                        hasError = true;
                    }

                    if (hasError) {
                        event.preventDefault();
                        showMessage("Les champs en rouge doivent être corrigés.", "danger");
                        return;
                    }

          if (!form.checkValidity()) {
            event.preventDefault();
                        showMessage("Veuillez vérifier les champs du formulaire.", "danger");
            return;
          }

                    showMessage("Inscription en cours...", "success");
        });
      })();
    </script>
</body>
</html>

