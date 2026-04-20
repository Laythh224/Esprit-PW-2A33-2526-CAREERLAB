<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
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
                        <h4 class="mb-0">Connexion</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($message !== ''): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>

                        <form id="loginForm" method="POST" novalidate>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control <?php echo $errors['email'] !== '' ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="votre@email.com" value="<?php echo htmlspecialchars($emailValue); ?>">
                                <div class="small text-danger d-block" id="emailError"><?php echo htmlspecialchars($errors['email']); ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control <?php echo $errors['password'] !== '' ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="********" value="<?php echo htmlspecialchars($passwordValue ?? ''); ?>">
                                <div class="small text-danger d-block" id="passwordError"><?php echo htmlspecialchars($errors['password']); ?></div>
                            </div>
                            <div id="validationPopups" class="position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>
                            <div class="d-grid">
                                <button type="submit" name="login" class="btn btn-primary">Se connecter</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-white border-0 pt-0 pb-3">
                        <a href="index.php?page=accueil" class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm border-0 mt-2 mb-2">&larr; Retour à l'accueil</a>
                        <br />
                        <a href="index.php?page=creer-compte">Créer un compte</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function () {
            const form = document.getElementById("loginForm");
            const emailInput = document.getElementById("email");
            const passwordInput = document.getElementById("password");
            const popupContainer = document.getElementById("validationPopups");
            const emailError = document.getElementById("emailError");
            const passwordError = document.getElementById("passwordError");

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

            function clearErrors() {
                emailError.textContent = "";
                passwordError.textContent = "";
                emailInput.classList.remove("is-invalid");
                passwordInput.classList.remove("is-invalid");
            }

            function setError(input, errorElement, text) {
                input.classList.add("is-invalid");
                errorElement.textContent = text;
                showPopup(text);
            }

            form.addEventListener("submit", function (event) {
                clearErrors();
                let hasError = false;

                if (!emailInput.value.trim()) {
                    setError(emailInput, emailError, "L'email est obligatoire.");
                    hasError = true;
                } else if (!isValidEmail(emailInput.value.trim())) {
                    setError(emailInput, emailError, "Veuillez saisir une adresse email valide.");
                    hasError = true;
                }

                if (!passwordInput.value) {
                    setError(passwordInput, passwordError, "Le mot de passe est obligatoire.");
                    hasError = true;
                }

                if (hasError) {
                    event.preventDefault();
                }
            });
        })();
    </script>
</body>
</html>
