
<!DOCTYPE html>
<html lang="fr">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Créer un compte Entreprise</title>
        <link rel="stylesheet" href="views/assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="views/assets/css/plugins.min.css" />
        <link rel="stylesheet" href="views/assets/css/kaiadmin.min.css" />
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
                            <h4 class="mb-0">Inscription Entreprise</h4>
                        </div>
                        <div class="card-body">
                                <?php if ($serverError !== ''): ?>
                                    <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($serverError); ?></div>
                                <?php endif; ?>
                            <form id="companySignupForm" method="POST" novalidate>
                                <div class="mb-3">
                                    <label for="nomEntreprise" class="form-label">Nom de l'entreprise</label>
                                    <input type="text" class="form-control" id="nomEntreprise" name="nom" placeholder="Nom de l'entreprise" />
                                    <div class="small text-danger d-block" id="nomEntrepriseError"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="contact@entreprise.com" />
                                    <div class="small text-danger d-block" id="emailError"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="********" />
                                    <div class="small text-danger d-block" id="passwordError"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                                    <input type="password" class="form-control" id="confirmPassword" placeholder="********" />
                                    <div class="small text-danger d-block" id="confirmPasswordError"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Téléphone de l'entreprise" />
                                    <div class="small text-danger d-block" id="telephoneError"></div>
                                </div>
                                <hr />
                                <h6 class="mb-3">Informations professionnelles</h6>
                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Adresse complète" />
                                    <div class="small text-danger d-block" id="adresseError"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="ville" class="form-label">Ville</label>
                                    <input type="text" class="form-control" id="ville" name="ville" placeholder="Ville" />
                                    <div class="small text-danger d-block" id="villeError"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="secteur" class="form-label">Secteur d'activité</label>
                                    <select class="form-select" id="secteur" name="secteur">
                                        <option value="" selected disabled>Choisissez un secteur</option>
                                        <option value="Informatique">Informatique</option>
                                        <option value="Finance">Finance</option>
                                        <option value="Industrie">Industrie</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                    <div class="small text-danger d-block" id="secteurError"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Présentation de l'entreprise"></textarea>
                                    <div class="small text-danger d-block" id="descriptionError"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="siteWeb" class="form-label">Site web (optionnel)</label>
                                    <input type="text" class="form-control" id="siteWeb" name="site" placeholder="https://votre-site.com" />
                                    <div class="small text-danger d-block" id="siteWebError"></div>
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
        <script>
            (function () {
                const form = document.getElementById("companySignupForm");
                    const nomEntrepriseInput = document.getElementById("nomEntreprise");
                    const emailInput = document.getElementById("email");
                const passwordInput = document.getElementById("password");
                const confirmPasswordInput = document.getElementById("confirmPassword");
                    const telephoneInput = document.getElementById("telephone");
                    const adresseInput = document.getElementById("adresse");
                    const villeInput = document.getElementById("ville");
                    const secteurInput = document.getElementById("secteur");
                    const descriptionInput = document.getElementById("description");
                    const siteWebInput = document.getElementById("siteWeb");
                const message = document.getElementById("formMessage");
                const popupContainer = document.getElementById("validationPopups");
                const requiredFields = [
                    { key: "nomEntreprise", input: nomEntrepriseInput, text: "Le nom de l'entreprise est obligatoire." },
                    { key: "email", input: emailInput, text: "L'email est obligatoire." },
                    { key: "password", input: passwordInput, text: "Le mot de passe est obligatoire." },
                    { key: "confirmPassword", input: confirmPasswordInput, text: "La confirmation du mot de passe est obligatoire." },
                    { key: "telephone", input: telephoneInput, text: "Le téléphone est obligatoire." },
                    { key: "adresse", input: adresseInput, text: "L'adresse est obligatoire." },
                    { key: "ville", input: villeInput, text: "La ville est obligatoire." },
                    { key: "secteur", input: secteurInput, text: "Le secteur d'activité est obligatoire." },
                    { key: "description", input: descriptionInput, text: "La description est obligatoire." },
                ];
                    const fieldErrors = {
                        nomEntreprise: document.getElementById("nomEntrepriseError"),
                        email: document.getElementById("emailError"),
                        password: document.getElementById("passwordError"),
                        confirmPassword: document.getElementById("confirmPasswordError"),
                        telephone: document.getElementById("telephoneError"),
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

                    function validateRequiredField(field) {
                        const value = field.input.value.trim();
                        if (value !== "") {
                            return true;
                        }

                        setFieldError(field.key, field.text);
                        showPopup(field.text);
                        return false;
                    }

                function showMessage(text, type) {
                    message.className = "alert alert-" + type;
                    message.textContent = text;
                    message.classList.remove("d-none");
                }

                requiredFields.forEach(function (field) {
                    field.input.addEventListener("blur", function () {
                        if (!field.input.value.trim()) {
                            setFieldError(field.key, field.text);
                            showPopup(field.text);
                        }
                    });

                    field.input.addEventListener("input", function () {
                        if (field.input.value.trim()) {
                            if (fieldErrors[field.key]) {
                                fieldErrors[field.key].textContent = "";
                            }
                            field.input.classList.remove("is-invalid");
                        }
                    });
                });

                form.addEventListener("submit", function (event) {
                        clearFieldErrors();

                        let hasError = false;

                        requiredFields.forEach(function (field) {
                            if (!validateRequiredField(field)) {
                                hasError = true;
                            }
                        });

                        if (emailInput.value.trim() && !isValidEmail(emailInput.value.trim())) {
                            setFieldError("email", "Veuillez saisir une adresse email valide.");
                            showPopup("Veuillez saisir une adresse email valide.");
                            hasError = true;
                        }

                        const siteValue = siteWebInput.value.trim();
                        if (siteValue !== "" && !siteValue.startsWith("http://") && !siteValue.startsWith("https://")) {
                            setFieldError("siteWeb", "Le site web doit commencer par http:// ou https://");
                            showPopup("Le site web doit commencer par http:// ou https://");
                            hasError = true;
                        }

                        if (passwordInput.value && confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                            setFieldError("confirmPassword", "Le mot de passe et la confirmation doivent être identiques.");
                            showPopup("Le mot de passe et la confirmation doivent être identiques.");
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

