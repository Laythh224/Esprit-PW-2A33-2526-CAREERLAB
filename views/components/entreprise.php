<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion Entreprises</title>
    <link rel="stylesheet" href="views/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="views/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="views/assets/css/kaiadmin.min.css" />
</head>
<body>
    <div class="wrapper">
        <?php $activeAccountPage = 'entreprise'; include __DIR__ . '/account-sidebar.php'; ?>
        <div class="main-panel">
            <?php include __DIR__ . '/account-header.php'; ?>
            <div class="container py-4">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" id="formTitle">Ajouter une entreprise</h4>
                                <form id="companyForm" novalidate>
                                    <input type="hidden" id="editId" />
                                    <div class="mb-3"><label for="nomEntreprise" class="form-label">Nom entreprise</label><input id="nomEntreprise" class="form-control" type="text" /></div>
                                    <div class="mb-3"><label for="email" class="form-label">Email</label><input id="email" class="form-control" type="text" /></div>
                                    <div class="mb-3"><label for="telephone" class="form-label">Telephone</label><input id="telephone" class="form-control" type="text" /></div>
                                    <div class="mb-3"><label for="adresse" class="form-label">Adresse</label><input id="adresse" class="form-control" type="text" /></div>
                                    <div class="mb-3"><label for="ville" class="form-label">Ville</label><input id="ville" class="form-control" type="text" /></div>
                                    <div class="mb-3"><label for="secteur" class="form-label">Secteur</label><input id="secteur" class="form-control" type="text" /></div>
                                    <div class="mb-3"><label for="siteWeb" class="form-label">Site web</label><input id="siteWeb" class="form-control" type="text" /></div>
                                    <div class="mb-3"><label for="description" class="form-label">Description</label><textarea id="description" class="form-control" rows="3"></textarea></div>
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
                                                <th>Entreprise</th>
                                                <th>Email</th>
                                                <th>Telephone</th>
                                                <th>Ville</th>
                                                <th>Secteur</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="companiesBody"></tbody>
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
        const API_URL = "index.php?page=api-entreprises";
        let companies = [];

        const companyForm = document.getElementById("companyForm");
        const editId = document.getElementById("editId");
        const nomEntrepriseInput = document.getElementById("nomEntreprise");
        const emailInput = document.getElementById("email");
        const telephoneInput = document.getElementById("telephone");
        const adresseInput = document.getElementById("adresse");
        const villeInput = document.getElementById("ville");
        const secteurInput = document.getElementById("secteur");
        const siteWebInput = document.getElementById("siteWeb");
        const descriptionInput = document.getElementById("description");
        const passwordInput = document.getElementById("password");
        const cancelBtn = document.getElementById("cancelBtn");
        const message = document.getElementById("message");
        const companiesBody = document.getElementById("companiesBody");
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
            companyForm.reset();
            editId.value = "";
            formTitle.textContent = "Ajouter une entreprise";
            message.textContent = "";
        }

        function renderTable() {
            companiesBody.innerHTML = "";
            if (companies.length === 0) {
                companiesBody.innerHTML = "<tr><td colspan='6' class='text-center text-muted'>Aucune entreprise.</td></tr>";
                return;
            }

            companies.forEach((u) => {
                const tr = document.createElement("tr");
                tr.innerHTML = ""
                    + "<td>" + escapeHtml(u.nom_entreprise || "") + "</td>"
                    + "<td>" + escapeHtml(u.email || "") + "</td>"
                    + "<td>" + escapeHtml(u.telephone || "-") + "</td>"
                    + "<td>" + escapeHtml(u.ville || "-") + "</td>"
                    + "<td>" + escapeHtml(u.secteur || "-") + "</td>"
                    + "<td>"
                    + "<button class='btn btn-sm btn-outline-primary me-1' data-action='edit' data-id='" + u.id + "'>Modifier</button>"
                    + "<button class='btn btn-sm btn-outline-danger' data-action='delete' data-id='" + u.id + "'>Supprimer</button>"
                    + "</td>";
                companiesBody.appendChild(tr);
            });
        }

        async function fetchCompanies() {
            const res = await fetch(API_URL);
            const data = await res.json();
            if (!data.ok) {
                throw new Error(data.message || "Impossible de charger les entreprises.");
            }
            companies = data.entreprises || [];
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

        companyForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            if (!nomEntrepriseInput.value.trim() || !emailInput.value.trim()) {
                showMessage("Nom entreprise et email sont obligatoires.", "error");
                return;
            }
            if (!isValidEmail(emailInput.value.trim())) {
                showMessage("Email invalide.", "error");
                return;
            }

            const payload = {
                nom_entreprise: nomEntrepriseInput.value.trim(),
                email: emailInput.value.trim(),
                telephone: telephoneInput.value.trim(),
                adresse: adresseInput.value.trim(),
                ville: villeInput.value.trim(),
                secteur: secteurInput.value.trim(),
                site_web: siteWebInput.value.trim(),
                description: descriptionInput.value.trim(),
                password: passwordInput.value
            };

            try {
                if (editId.value) {
                    await sendAction({ action: "update", id: Number(editId.value), ...payload });
                    showMessage("Entreprise modifiee.", "ok");
                } else {
                    if (!payload.password) {
                        showMessage("Le mot de passe est obligatoire a la creation.", "error");
                        return;
                    }
                    await sendAction({ action: "create", ...payload });
                    showMessage("Entreprise ajoutee.", "ok");
                }

                resetForm();
                await fetchCompanies();
            } catch (error) {
                showMessage(error.message, "error");
            }
        });

        cancelBtn.addEventListener("click", resetForm);

        companiesBody.addEventListener("click", async function (event) {
            const button = event.target.closest("button[data-action]");
            if (!button) {
                return;
            }

            const action = button.getAttribute("data-action");
            const id = Number(button.getAttribute("data-id"));
            const company = companies.find((u) => Number(u.id) === id);

            if (action === "edit" && company) {
                editId.value = String(company.id);
                nomEntrepriseInput.value = company.nom_entreprise || "";
                emailInput.value = company.email || "";
                telephoneInput.value = company.telephone || "";
                adresseInput.value = company.adresse || "";
                villeInput.value = company.ville || "";
                secteurInput.value = company.secteur || "";
                siteWebInput.value = company.site_web || "";
                descriptionInput.value = company.description || "";
                passwordInput.value = "";
                formTitle.textContent = "Modifier une entreprise";
                showMessage("", "ok");
                return;
            }

            if (action === "delete") {
                if (!confirm("Supprimer cette entreprise ?")) {
                    return;
                }

                try {
                    await sendAction({ action: "delete", id: id });
                    showMessage("Entreprise supprimee.", "ok");
                    if (String(id) === editId.value) {
                        resetForm();
                    }
                    await fetchCompanies();
                } catch (error) {
                    showMessage(error.message, "error");
                }
            }
        });

        fetchCompanies().catch(function (error) {
            showMessage(error.message, "error");
        });
    </script>
</body>
</html>

