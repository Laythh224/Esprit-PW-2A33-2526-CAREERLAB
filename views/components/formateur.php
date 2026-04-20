<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion Formateurs</title>
    <link rel="stylesheet" href="views/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="views/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="views/assets/css/kaiadmin.min.css" />
</head>
<body>
    <div class="wrapper">
        <?php $activeAccountPage = 'formateur'; include __DIR__ . '/account-sidebar.php'; ?>
        <div class="main-panel">
            <?php include __DIR__ . '/account-header.php'; ?>
            <div class="container py-4">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" id="formTitle">Ajouter un formateur</h4>
                                <form id="trainerForm" novalidate>
                                    <input type="hidden" id="editId" />
                                    <div class="mb-3"><label for="nom" class="form-label">Nom</label><input id="nom" class="form-control" type="text" /><div class="small text-danger d-block" id="nomError"></div></div>
                                    <div class="mb-3"><label for="prenom" class="form-label">Prenom</label><input id="prenom" class="form-control" type="text" /><div class="small text-danger d-block" id="prenomError"></div></div>
                                    <div class="mb-3"><label for="email" class="form-label">Email</label><input id="email" class="form-control" type="text" /><div class="small text-danger d-block" id="emailError"></div></div>
                                    <div class="mb-3"><label for="telephone" class="form-label">Telephone</label><input id="telephone" class="form-control" type="text" /><div class="small text-danger d-block" id="telephoneError"></div></div>
                                    <div class="mb-3"><label for="specialite" class="form-label">Specialite</label><input id="specialite" class="form-control" type="text" /><div class="small text-danger d-block" id="specialiteError"></div></div>
                                    <div class="mb-3"><label for="diplomes" class="form-label">Diplomes</label><input id="diplomes" class="form-control" type="text" /><div class="small text-danger d-block" id="diplomesError"></div></div>
                                    <div class="mb-3"><label for="experience" class="form-label">Experience</label><textarea id="experience" class="form-control" rows="3"></textarea><div class="small text-danger d-block" id="experienceError"></div></div>
                                    <div class="mb-3"><label for="password" class="form-label">Mot de passe (obligatoire a la creation)</label><input id="password" class="form-control" type="password" /><div class="small text-danger d-block" id="passwordError"></div></div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary" type="submit">Enregistrer</button>
                                        <button class="btn btn-outline-secondary" type="button" id="cancelBtn">Annuler</button>
                                    </div>
                                </form>
                                <p id="message" class="mt-3 mb-0 small"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped align-middle">
                                        <thead>
                                            <tr>
                                                <th>Nom complet</th>
                                                <th>Email</th>
                                                <th>Telephone</th>
                                                <th>Specialite</th>
                                                <th>Diplomes</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="trainersBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="views/assets/js/jquery-3.7.1.min.js"></script>
    <script src="views/assets/js/popper.min.js"></script>
    <script src="views/assets/js/bootstrap.min.js"></script>
    <script src="views/assets/js/kaiadmin.min.js"></script>
    <script>
        const API_URL = "index.php?page=api-formateurs";
        let trainers = [];

        const trainerForm = document.getElementById("trainerForm");
        const editId = document.getElementById("editId");
        const nomInput = document.getElementById("nom");
        const prenomInput = document.getElementById("prenom");
        const emailInput = document.getElementById("email");
        const telephoneInput = document.getElementById("telephone");
        const specialiteInput = document.getElementById("specialite");
        const diplomesInput = document.getElementById("diplomes");
        const experienceInput = document.getElementById("experience");
        const passwordInput = document.getElementById("password");
        const cancelBtn = document.getElementById("cancelBtn");
        const message = document.getElementById("message");
        const trainersBody = document.getElementById("trainersBody");
        const formTitle = document.getElementById("formTitle");
        const fieldErrors = {
            nom: document.getElementById("nomError"),
            prenom: document.getElementById("prenomError"),
            email: document.getElementById("emailError"),
            telephone: document.getElementById("telephoneError"),
            specialite: document.getElementById("specialiteError"),
            diplomes: document.getElementById("diplomesError"),
            experience: document.getElementById("experienceError"),
            password: document.getElementById("passwordError")
        };
        const fieldInputs = {
            nom: nomInput,
            prenom: prenomInput,
            email: emailInput,
            telephone: telephoneInput,
            specialite: specialiteInput,
            diplomes: diplomesInput,
            experience: experienceInput,
            password: passwordInput
        };

        function escapeHtml(value) {
            const div = document.createElement("div");
            div.textContent = value ?? "";
            return div.innerHTML;
        }

        function showMessage(text, type) {
            message.textContent = text;
            message.className = type === "ok" ? "mt-3 mb-0 small text-success" : "mt-3 mb-0 small text-danger";
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function clearFieldErrors() {
            Object.keys(fieldErrors).forEach((key) => {
                if (fieldErrors[key]) {
                    fieldErrors[key].textContent = "";
                }
                if (fieldInputs[key]) {
                    fieldInputs[key].classList.remove("is-invalid");
                }
            });
        }

        function setFieldError(key, text) {
            if (fieldErrors[key]) {
                fieldErrors[key].textContent = text;
            }
            if (fieldInputs[key]) {
                fieldInputs[key].classList.add("is-invalid");
                fieldInputs[key].value = "";
            }
        }

        function resetForm() {
            trainerForm.reset();
            editId.value = "";
            formTitle.textContent = "Ajouter un formateur";
            message.textContent = "";
            clearFieldErrors();
        }

        function renderTable() {
            trainersBody.innerHTML = "";
            if (trainers.length === 0) {
                trainersBody.innerHTML = "<tr><td colspan='6' class='text-center text-muted'>Aucun formateur.</td></tr>";
                return;
            }

            trainers.forEach((u) => {
                const tr = document.createElement("tr");
                tr.innerHTML = ""
                    + "<td>" + escapeHtml((u.prenom || "") + " " + (u.nom || "")) + "</td>"
                    + "<td>" + escapeHtml(u.email || "") + "</td>"
                    + "<td>" + escapeHtml(u.telephone || "-") + "</td>"
                    + "<td>" + escapeHtml(u.specialite || "-") + "</td>"
                    + "<td>" + escapeHtml(u.diplomes || "-") + "</td>"
                    + "<td>"
                    + "<button class='btn btn-sm btn-outline-primary me-1' data-action='edit' data-id='" + u.id + "'>Modifier</button>"
                    + "<button class='btn btn-sm btn-outline-danger' data-action='delete' data-id='" + u.id + "'>Supprimer</button>"
                    + "</td>";
                trainersBody.appendChild(tr);
            });
        }

        async function fetchTrainers() {
            const res = await fetch(API_URL);
            const data = await res.json();
            if (!data.ok) {
                throw new Error(data.message || "Impossible de charger les formateurs.");
            }
            trainers = data.formateurs || [];
            renderTable();
        }

        async function sendAction(payload) {
            const res = await fetch(API_URL, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (!data.ok) {
                throw new Error(data.message || "Action impossible.");
            }
            return data;
        }

        trainerForm.addEventListener("submit", async function (event) {
            event.preventDefault();
            clearFieldErrors();

            let hasError = false;

            if (!nomInput.value.trim()) {
                setFieldError("nom", "Le nom est obligatoire.");
                hasError = true;
            }
            if (!prenomInput.value.trim()) {
                setFieldError("prenom", "Le prenom est obligatoire.");
                hasError = true;
            }
            if (!emailInput.value.trim()) {
                setFieldError("email", "L'email est obligatoire.");
                hasError = true;
            }

            if (emailInput.value.trim() && !isValidEmail(emailInput.value.trim())) {
                setFieldError("email", "Email invalide.");
                hasError = true;
            }

            if (!editId.value && !passwordInput.value) {
                setFieldError("password", "Le mot de passe est obligatoire a la creation.");
                hasError = true;
            }

            if (hasError) {
                showMessage("Veuillez corriger les champs invalides.", "error");
                return;
            }

            const payload = {
                nom: nomInput.value.trim(),
                prenom: prenomInput.value.trim(),
                email: emailInput.value.trim(),
                telephone: telephoneInput.value.trim(),
                specialite: specialiteInput.value.trim(),
                diplomes: diplomesInput.value.trim(),
                experience: experienceInput.value.trim(),
                password: passwordInput.value
            };

            try {
                if (editId.value) {
                    await sendAction({ action: "update", id: Number(editId.value), ...payload });
                    showMessage("Formateur modifie.", "ok");
                } else {
                    await sendAction({ action: "create", ...payload });
                    showMessage("Formateur ajoute.", "ok");
                }

                resetForm();
                await fetchTrainers();
            } catch (error) {
                showMessage(error.message, "error");
            }
        });

        cancelBtn.addEventListener("click", resetForm);

        Object.keys(fieldInputs).forEach((key) => {
            fieldInputs[key].addEventListener("input", function () {
                if (fieldErrors[key]) {
                    fieldErrors[key].textContent = "";
                }
                fieldInputs[key].classList.remove("is-invalid");
            });
        });

        trainersBody.addEventListener("click", async function (event) {
            const button = event.target.closest("button[data-action]");
            if (!button) {
                return;
            }

            const action = button.getAttribute("data-action");
            const id = Number(button.getAttribute("data-id"));
            const trainer = trainers.find((u) => Number(u.id) === id);

            if (action === "edit" && trainer) {
                editId.value = String(trainer.id);
                nomInput.value = trainer.nom || "";
                prenomInput.value = trainer.prenom || "";
                emailInput.value = trainer.email || "";
                telephoneInput.value = trainer.telephone || "";
                specialiteInput.value = trainer.specialite || "";
                diplomesInput.value = trainer.diplomes || "";
                experienceInput.value = trainer.experience || "";
                passwordInput.value = "";
                formTitle.textContent = "Modifier un formateur";
                showMessage("", "ok");
                return;
            }

            if (action === "delete") {
                if (!confirm("Supprimer ce formateur ?")) {
                    return;
                }

                try {
                    await sendAction({ action: "delete", id: id });
                    showMessage("Formateur supprime.", "ok");
                    if (String(id) === editId.value) {
                        resetForm();
                    }
                    await fetchTrainers();
                } catch (error) {
                    showMessage(error.message, "error");
                }
            }
        });

        fetchTrainers().catch(function (error) {
            showMessage(error.message, "error");
        });
    </script>
</body>
</html>

