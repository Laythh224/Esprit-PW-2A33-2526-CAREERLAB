<?php
$old = $old ?? [];
$fieldErrors = $fieldErrors ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Créer un compte Formateur</title>
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
                            <h4 class="mb-0">Inscription Formateur</h4>
                        </div>
                        <div class="card-body">
                            <form id="trainerSignupForm" method="POST" enctype="multipart/form-data" novalidate>
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom</label>
                                    <input type="text" class="form-control <?php echo ($fieldErrors['nom'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="nom" name="nom" placeholder="Votre nom" value="<?php echo htmlspecialchars((string) ($old['nom'] ?? '')); ?>" />
                                    <div class="small text-danger d-block" id="nomError"><?php echo htmlspecialchars((string) ($fieldErrors['nom'] ?? '')); ?></div>
                                </div>
                                <div class="mb-3">
                                    <label for="prenom" class="form-label">Prénom</label>
                                    <input type="text" class="form-control <?php echo ($fieldErrors['prenom'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="prenom" name="prenom" placeholder="Votre prénom" value="<?php echo htmlspecialchars((string) ($old['prenom'] ?? '')); ?>" />
                                    <div class="small text-danger d-block" id="prenomError"><?php echo htmlspecialchars((string) ($fieldErrors['prenom'] ?? '')); ?></div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control <?php echo ($fieldErrors['email'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="votre@email.com" value="<?php echo htmlspecialchars((string) ($old['email'] ?? '')); ?>" />
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
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="text" class="form-control <?php echo ($fieldErrors['telephone'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="telephone" name="telephone" placeholder="Votre téléphone" value="<?php echo htmlspecialchars((string) ($old['telephone'] ?? '')); ?>" />
                                    <div class="small text-danger d-block" id="telephoneError"><?php echo htmlspecialchars((string) ($fieldErrors['telephone'] ?? '')); ?></div>
                                </div>
                                <hr />
                                <h6 class="mb-3">Informations professionnelles</h6>
                                <div class="mb-3">
                                    <label for="specialite" class="form-label">Spécialité</label>
                                    <input type="text" class="form-control <?php echo ($fieldErrors['specialite'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="specialite" name="specialite" placeholder="Ex : Développement Web" value="<?php echo htmlspecialchars((string) ($old['specialite'] ?? '')); ?>" />
                                    <div class="small text-danger d-block" id="specialiteError"><?php echo htmlspecialchars((string) ($fieldErrors['specialite'] ?? '')); ?></div>
                                </div>
                                <div class="mb-3">
                                    <label for="diplomes" class="form-label">Diplômes</label>
                                    <textarea class="form-control <?php echo ($fieldErrors['diplomes'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="diplomes" name="diplomes" rows="3" placeholder="Ex : Master Informatique"><?php echo htmlspecialchars((string) ($old['diplomes'] ?? '')); ?></textarea>
                                    <div class="small text-danger d-block" id="diplomesError"><?php echo htmlspecialchars((string) ($fieldErrors['diplomes'] ?? '')); ?></div>
                                </div>
                                <div class="mb-3">
                                    <label for="experience" class="form-label">Expérience</label>
                                    <textarea class="form-control <?php echo ($fieldErrors['experience'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="experience" name="experience" rows="3" placeholder="Ex : 3 ans d'enseignement"><?php echo htmlspecialchars((string) ($old['experience'] ?? '')); ?></textarea>
                                    <div class="small text-danger d-block" id="experienceError"><?php echo htmlspecialchars((string) ($fieldErrors['experience'] ?? '')); ?></div>
                                </div>
                                <div class="mb-3">
                                    <label for="cv" class="form-label">CV (PDF)</label>
                                    <input type="file" class="form-control <?php echo ($fieldErrors['cv'] ?? '') !== '' ? 'is-invalid' : ''; ?>" id="cv" name="cv" accept="application/pdf,.pdf" />
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
                    const diplomesInput = document.getElementById("diplomes");
                    const experienceInput = document.getElementById("experience");
                const cvInput = document.getElementById("cv");
                const message = document.getElementById("formMessage");
                const popupContainer = document.getElementById("validationPopups");
                const requiredFields = [
                    { key: "nom", input: nomInput, text: "Le nom est obligatoire." },
                    { key: "prenom", input: prenomInput, text: "Le prénom est obligatoire." },
                    { key: "email", input: emailInput, text: "L'email est obligatoire." },
                    { key: "password", input: passwordInput, text: "Le mot de passe est obligatoire." },
                    { key: "confirmPassword", input: confirmPasswordInput, text: "La confirmation du mot de passe est obligatoire." },
                        { key: "telephone", input: telephoneInput, text: "Le téléphone est obligatoire." },
                    { key: "specialite", input: specialiteInput, text: "La spécialité est obligatoire." },
                    { key: "diplomes", input: diplomesInput, text: "Les diplômes sont obligatoires." },
                    { key: "experience", input: experienceInput, text: "L'expérience est obligatoire." },
                ];
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
                        diplomes: diplomesInput,
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

                cvInput.addEventListener("change", function () {
                    const cvFile = cvInput.files[0];
                    if (!cvFile) {
                        setFieldError("cv", "Le CV est obligatoire.");
                        showPopup("Le CV est obligatoire.");
                        return;
                    }

                    if (!cvFile.name.toLowerCase().endsWith(".pdf")) {
                        setFieldError("cv", "Le CV doit être au format PDF.");
                        showPopup("Le CV doit être au format PDF.");
                        return;
                    }

                    fieldErrors.cv.textContent = "";
                    cvInput.classList.remove("is-invalid");
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

                        if (telephoneInput.value.trim() && !/^\d{8}$/.test(telephoneInput.value.trim())) {
                            setFieldError("telephone", "Le téléphone doit contenir 8 chiffres.");
                            showPopup("Le téléphone doit contenir 8 chiffres.");
                            hasError = true;
                        }

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

