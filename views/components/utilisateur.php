<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion Utilisateurs</title>
    <link rel="stylesheet" href="views/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="views/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="views/assets/css/kaiadmin.min.css" />
</head>
<body>
    <div class="wrapper">
        <?php $activeAccountPage = 'utilisateur'; include __DIR__ . '/account-sidebar.php'; ?>
        <div class="main-panel">
            <?php include __DIR__ . '/account-header.php'; ?>
            <div class="container py-4">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" id="formTitle">Ajouter un utilisateur</h4>
                                <form id="userForm" novalidate>
                                    <input type="hidden" id="editId" />
                                    <div class="mb-3"><label for="nom" class="form-label">Nom</label><input id="nom" class="form-control" type="text" /></div>
                                    <div class="mb-3"><label for="prenom" class="form-label">Prenom</label><input id="prenom" class="form-control" type="text" /></div>
                                    <div class="mb-3"><label for="email" class="form-label">Email</label><input id="email" class="form-control" type="text" /></div>
                                    <div class="mb-3"><label for="telephone" class="form-label">Telephone</label><input id="telephone" class="form-control" type="text" /></div>
                                    <div class="mb-3"><label for="niveau" class="form-label">Niveau d'etude</label><input id="niveau" class="form-control" type="text" /></div>
                                    <div class="mb-3"><label for="domaine" class="form-label">Domaine</label><input id="domaine" class="form-control" type="text" /></div>
                                    <div class="mb-3"><label for="password" class="form-label">Mot de passe (obligatoire a la creation)</label><input id="password" class="form-control" type="password" /></div>
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
                                                <th>Niveau</th>
                                                <th>Domaine</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="usersBody"></tbody>
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
        const API_URL = "index.php?page=api-utilisateurs";
        let users = [];

        const userForm = document.getElementById("userForm");
        const editId = document.getElementById("editId");
        const nomInput = document.getElementById("nom");
        const prenomInput = document.getElementById("prenom");
        const emailInput = document.getElementById("email");
        const telephoneInput = document.getElementById("telephone");
        const niveauInput = document.getElementById("niveau");
        const domaineInput = document.getElementById("domaine");
        const passwordInput = document.getElementById("password");
        const cancelBtn = document.getElementById("cancelBtn");
        const message = document.getElementById("message");
        const usersBody = document.getElementById("usersBody");
        const formTitle = document.getElementById("formTitle");

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

        function resetForm() {
            userForm.reset();
            editId.value = "";
            formTitle.textContent = "Ajouter un utilisateur";
            message.textContent = "";
        }

        function renderTable() {
            usersBody.innerHTML = "";
            if (users.length === 0) {
                usersBody.innerHTML = "<tr><td colspan='6' class='text-center text-muted'>Aucun utilisateur.</td></tr>";
                return;
            }

            users.forEach((u) => {
                const tr = document.createElement("tr");
                tr.innerHTML = ""
                    + "<td>" + escapeHtml((u.prenom || "") + " " + (u.nom || "")) + "</td>"
                    + "<td>" + escapeHtml(u.email || "") + "</td>"
                    + "<td>" + escapeHtml(u.telephone || "-") + "</td>"
                    + "<td>" + escapeHtml(u.niveau_etude || "-") + "</td>"
                    + "<td>" + escapeHtml(u.domaine || "-") + "</td>"
                    + "<td>"
                    + "<button class='btn btn-sm btn-outline-primary me-1' data-action='edit' data-id='" + u.id + "'>Modifier</button>"
                    + "<button class='btn btn-sm btn-outline-danger' data-action='delete' data-id='" + u.id + "'>Supprimer</button>"
                    + "</td>";
                usersBody.appendChild(tr);
            });
        }

        async function fetchUsers() {
            const res = await fetch(API_URL);
            const data = await res.json();
            if (!data.ok) {
                throw new Error(data.message || "Impossible de charger les utilisateurs.");
            }
            users = data.users || [];
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

        userForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            if (!nomInput.value.trim() || !prenomInput.value.trim() || !emailInput.value.trim()) {
                showMessage("Nom, prenom et email sont obligatoires.", "error");
                return;
            }
            if (!isValidEmail(emailInput.value.trim())) {
                showMessage("Email invalide.", "error");
                return;
            }

            const payload = {
                nom: nomInput.value.trim(),
                prenom: prenomInput.value.trim(),
                email: emailInput.value.trim(),
                telephone: telephoneInput.value.trim(),
                niveau_etude: niveauInput.value.trim(),
                domaine: domaineInput.value.trim(),
                password: passwordInput.value
            };

            try {
                if (editId.value) {
                    await sendAction({ action: "update", id: Number(editId.value), ...payload });
                    showMessage("Utilisateur modifie.", "ok");
                } else {
                    if (!payload.password) {
                        showMessage("Le mot de passe est obligatoire a la creation.", "error");
                        return;
                    }
                    await sendAction({ action: "create", ...payload });
                    showMessage("Utilisateur ajoute.", "ok");
                }

                resetForm();
                await fetchUsers();
            } catch (error) {
                showMessage(error.message, "error");
            }
        });

        cancelBtn.addEventListener("click", resetForm);

        usersBody.addEventListener("click", async function (event) {
            const button = event.target.closest("button[data-action]");
            if (!button) {
                return;
            }

            const action = button.getAttribute("data-action");
            const id = Number(button.getAttribute("data-id"));
            const user = users.find((u) => Number(u.id) === id);

            if (action === "edit" && user) {
                editId.value = String(user.id);
                nomInput.value = user.nom || "";
                prenomInput.value = user.prenom || "";
                emailInput.value = user.email || "";
                telephoneInput.value = user.telephone || "";
                niveauInput.value = user.niveau_etude || "";
                domaineInput.value = user.domaine || "";
                passwordInput.value = "";
                formTitle.textContent = "Modifier un utilisateur";
                showMessage("", "ok");
                return;
            }

            if (action === "delete") {
                if (!confirm("Supprimer cet utilisateur ?")) {
                    return;
                }

                try {
                    await sendAction({ action: "delete", id: id });
                    showMessage("Utilisateur supprime.", "ok");
                    if (String(id) === editId.value) {
                        resetForm();
                    }
                    await fetchUsers();
                } catch (error) {
                    showMessage(error.message, "error");
                }
            }
        });

        fetchUsers().catch(function (error) {
            showMessage(error.message, "error");
        });
    </script>
</body>
</html>

