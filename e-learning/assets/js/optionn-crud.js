(function () {
  const STORAGE_KEY = "elearning_optionn";
  const PLANNING_STORAGE_KEY = "elearning_planning";
  const PLANNING_OPTION_STORAGE_KEY = "elearning_planning_option";

  const defaultOptions = [
    {
      id_option: 1,
      nom_option: "Développement web",
      specialite: "Front-end",
      description: "Bases HTML/CSS et intégration",
    },
    {
      id_option: 2,
      nom_option: "Communication professionnelle",
      specialite: "Soft skills",
      description: "Prise de parole et feedback en équipe",
    },
    {
      id_option: 3,
      nom_option: "Découverte IA",
      specialite: "Data",
      description: "Introduction aux usages de l'intelligence artificielle",
    },
  ];

  const defaultPlanning = [
    {
      id_planning: 1,
      nom_de_formation: "Session printemps web",
      date_debut: "2026-04-01",
      date_fin: "2026-05-15",
      TYPE: "En ligne",
    },
    {
      id_planning: 2,
      nom_de_formation: "Session communication",
      date_debut: "2026-05-20",
      date_fin: "2026-06-10",
      TYPE: "Présentiel",
    },
    {
      id_planning: 3,
      nom_de_formation: "Session IA débutant",
      date_debut: "2026-02-10",
      date_fin: "2026-03-05",
      TYPE: "Hybride",
    },
  ];

  const defaultPlanningOption = [
    { id_planning: 1, id_option: 1 },
    { id_planning: 1, id_option: 3 },
    { id_planning: 2, id_option: 2 },
    { id_planning: 3, id_option: 3 },
  ];

  function loadOptionn() {
    const raw = localStorage.getItem(STORAGE_KEY);
    if (!raw) {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(defaultOptions));
      return [...defaultOptions];
    }

    try {
      const parsed = JSON.parse(raw);
      if (!Array.isArray(parsed)) {
        throw new Error("Format invalide");
      }
      return parsed;
    } catch (error) {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(defaultOptions));
      return [...defaultOptions];
    }
  }

  function saveOptionn(items) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
  }

  function loadPlanning() {
    const raw = localStorage.getItem(PLANNING_STORAGE_KEY);
    if (!raw) {
      localStorage.setItem(PLANNING_STORAGE_KEY, JSON.stringify(defaultPlanning));
      return [...defaultPlanning];
    }

    try {
      const parsed = JSON.parse(raw);
      if (!Array.isArray(parsed)) {
        throw new Error("Format invalide");
      }
      return parsed;
    } catch (error) {
      localStorage.setItem(PLANNING_STORAGE_KEY, JSON.stringify(defaultPlanning));
      return [...defaultPlanning];
    }
  }

  function savePlanning(items) {
    localStorage.setItem(PLANNING_STORAGE_KEY, JSON.stringify(items));
  }

  function loadPlanningOption() {
    const raw = localStorage.getItem(PLANNING_OPTION_STORAGE_KEY);
    if (!raw) {
      localStorage.setItem(PLANNING_OPTION_STORAGE_KEY, JSON.stringify(defaultPlanningOption));
      return [...defaultPlanningOption];
    }

    try {
      const parsed = JSON.parse(raw);
      if (!Array.isArray(parsed)) {
        throw new Error("Format invalide");
      }
      return parsed;
    } catch (error) {
      localStorage.setItem(PLANNING_OPTION_STORAGE_KEY, JSON.stringify(defaultPlanningOption));
      return [...defaultPlanningOption];
    }
  }

  function savePlanningOption(items) {
    localStorage.setItem(PLANNING_OPTION_STORAGE_KEY, JSON.stringify(items));
  }

  function getNextId(items) {
    if (items.length === 0) {
      return 1;
    }
    return Math.max(...items.map((item) => Number(item.id_option) || 0)) + 1;
  }

  function getNextNumericId(items, keyName) {
    if (items.length === 0) {
      return 1;
    }
    return Math.max(...items.map((item) => Number(item[keyName]) || 0)) + 1;
  }

  function calculateDurationDays(startDate, endDate) {
    const start = new Date(startDate);
    const end = new Date(endDate);

    if (Number.isNaN(start.getTime()) || Number.isNaN(end.getTime())) {
      return "-";
    }

    const diffMs = end.getTime() - start.getTime();
    if (diffMs < 0) {
      return "-";
    }

    return Math.floor(diffMs / (1000 * 60 * 60 * 24));
  }

  function calculatePlanningStatus(startDate, endDate) {
    const start = new Date(startDate);
    const end = new Date(endDate);
    const today = new Date();
    const todayOnly = new Date(today.getFullYear(), today.getMonth(), today.getDate());

    if (Number.isNaN(start.getTime()) || Number.isNaN(end.getTime())) {
      return "Invalide";
    }

    if (todayOnly < start) {
      return "À venir";
    }

    if (todayOnly > end) {
      return "Terminé";
    }

    return "En cours";
  }

  function getParams() {
    return new URLSearchParams(window.location.search);
  }

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/\"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  function renderBackTable() {
    const tbody = document.getElementById("optionn-back-table-body");
    if (!tbody) {
      return;
    }

    const items = loadOptionn();
    tbody.innerHTML = items
      .map(
        (item) => `
          <tr>
            <td>${escapeHtml(item.id_option)}</td>
            <td>${escapeHtml(item.nom_option || "")}</td>
            <td>${escapeHtml(item.specialite || "")}</td>
            <td>${escapeHtml(item.description || "")}</td>
            <td>
              <button type="button" class="btn-sm btn-sm--edit" data-action="edit" data-id="${escapeHtml(item.id_option)}">Modifier</button>
              <button type="button" class="btn-sm btn-sm--danger" data-action="delete" data-id="${escapeHtml(item.id_option)}">Supprimer</button>
            </td>
          </tr>
        `
      )
      .join("");

    tbody.querySelectorAll("button[data-action='edit']").forEach((button) => {
      button.addEventListener("click", function () {
        const id = Number(this.getAttribute("data-id"));
        const inlineForm = document.getElementById("optionn-back-inline-form");
        if (inlineForm) {
          const item = loadOptionn().find((entry) => Number(entry.id_option) === id);
          if (!item) {
            return;
          }

          const idInput = document.getElementById("optionn_back_id_option");
          const nomInput = document.getElementById("optionn_back_nom_option");
          const specialiteInput = document.getElementById("optionn_back_specialite");
          const descriptionInput = document.getElementById("optionn_back_description");
          const title = document.getElementById("optionn-back-inline-title");

          if (idInput) {
            idInput.value = String(item.id_option);
          }
          if (nomInput) {
            nomInput.value = item.nom_option || "";
          }
          if (specialiteInput) {
            specialiteInput.value = item.specialite || "";
          }
          if (descriptionInput) {
            descriptionInput.value = item.description || "";
          }
          if (title) {
            title.textContent = `Modifier option #${item.id_option}`;
          }
          return;
        }

        window.location.href = `formation-editer.html?id=${id}`;
      });
    });

    tbody.querySelectorAll("button[data-action='delete']").forEach((button) => {
      button.addEventListener("click", function () {
        const id = Number(this.getAttribute("data-id"));
        const confirmed = window.confirm("Supprimer cette option ?");
        if (!confirmed) {
          return;
        }

        const next = loadOptionn().filter((item) => Number(item.id_option) !== id);
        saveOptionn(next);
        const nextLinks = loadPlanningOption().filter((link) => Number(link.id_option) !== id);
        savePlanningOption(nextLinks);
        renderBackTable();
        renderPlanningOptionSelectors();
        renderPlanningOptionBackTable();
      });
    });
  }

  function clearOptionnBackInlineForm() {
    const form = document.getElementById("optionn-back-inline-form");
    if (!form) {
      return;
    }

    form.reset();
    const idInput = document.getElementById("optionn_back_id_option");
    if (idInput) {
      idInput.value = "";
    }
    const title = document.getElementById("optionn-back-inline-title");
    if (title) {
      title.textContent = "Ajouter une option";
    }
  }

  function bindOptionnBackInlineForm() {
    const form = document.getElementById("optionn-back-inline-form");
    if (!form) {
      return;
    }

    const idInput = document.getElementById("optionn_back_id_option");
    const nomInput = document.getElementById("optionn_back_nom_option");
    const specialiteInput = document.getElementById("optionn_back_specialite");
    const descriptionInput = document.getElementById("optionn_back_description");
    const cancelButton = document.getElementById("optionn-back-inline-cancel");

    form.addEventListener("submit", function (event) {
      event.preventDefault();

      const nom = nomInput.value.trim();
      const specialite = specialiteInput.value.trim();
      const description = descriptionInput.value.trim();
      const idValue = Number(idInput.value);

      if (!nom) {
        window.alert("Le champ nom_option est obligatoire.");
        return;
      }

      const items = loadOptionn();
      if (idValue) {
        const updated = items.map((item) =>
          Number(item.id_option) === idValue
            ? {
                id_option: idValue,
                nom_option: nom,
                specialite,
                description,
              }
            : item
        );
        saveOptionn(updated);
      } else {
        items.push({
          id_option: getNextId(items),
          nom_option: nom,
          specialite,
          description,
        });
        saveOptionn(items);
      }

      renderBackTable();
      renderPlanningOptionSelectors();
      renderPlanningOptionBackTable();
      clearOptionnBackInlineForm();
    });

    if (cancelButton) {
      cancelButton.addEventListener("click", function () {
        clearOptionnBackInlineForm();
      });
    }
  }

  function bindBackEditorForm() {
    const form = document.getElementById("optionn-back-form");
    if (!form) {
      return;
    }

    const idInput = document.getElementById("id_option");
    const nomInput = document.getElementById("nom_option");
    const specialiteInput = document.getElementById("specialite");
    const descriptionInput = document.getElementById("description");
    const cancelButton = document.getElementById("optionn-cancel-btn");
    const title = document.getElementById("optionn-editor-title");

    const params = getParams();
    const editId = Number(params.get("id"));

    if (editId) {
      const existing = loadOptionn().find((item) => Number(item.id_option) === editId);
      if (existing) {
        idInput.value = String(existing.id_option);
        nomInput.value = existing.nom_option || "";
        specialiteInput.value = existing.specialite || "";
        descriptionInput.value = existing.description || "";
        if (title) {
          title.textContent = `Modifier option #${existing.id_option}`;
        }
      }
    }

    form.addEventListener("submit", function (event) {
      event.preventDefault();

      const nom = nomInput.value.trim();
      const specialite = specialiteInput.value.trim();
      const description = descriptionInput.value.trim();

      if (!nom) {
        window.alert("Le champ nom_option est obligatoire.");
        return;
      }

      const items = loadOptionn();
      const idValue = Number(idInput.value);

      if (idValue) {
        const updated = items.map((item) =>
          Number(item.id_option) === idValue
            ? {
                id_option: idValue,
                nom_option: nom,
                specialite,
                description,
              }
            : item
        );
        saveOptionn(updated);
      } else {
        const newItem = {
          id_option: getNextId(items),
          nom_option: nom,
          specialite,
          description,
        };
        items.push(newItem);
        saveOptionn(items);
      }

      window.location.href = "formations-gestion.html";
    });

    if (cancelButton) {
      cancelButton.addEventListener("click", function () {
        window.location.href = "formations-gestion.html";
      });
    }
  }

  function clearPlanningForm() {
    const form = document.getElementById("planning-back-form");
    if (!form) {
      return;
    }

    form.reset();
    const idInput = document.getElementById("id_planning");
    if (idInput) {
      idInput.value = "";
    }
  }

  function clearPlanningOptionForm() {
    const form = document.getElementById("planning-option-back-form");
    if (!form) {
      return;
    }

    form.reset();
    const originalPlanning = document.getElementById("po_original_id_planning");
    const originalOption = document.getElementById("po_original_id_option");
    if (originalPlanning) {
      originalPlanning.value = "";
    }
    if (originalOption) {
      originalOption.value = "";
    }
  }

  function renderPlanningOptionSelectors() {
    const planningSelect = document.getElementById("po_id_planning");
    const optionSelect = document.getElementById("po_id_option");
    if (!planningSelect || !optionSelect) {
      return;
    }

    const planningItems = loadPlanning();
    const optionItems = loadOptionn();

    planningSelect.innerHTML = planningItems
      .map(
        (item) =>
          `<option value="${escapeHtml(item.id_planning)}">${escapeHtml(item.id_planning)} — ${escapeHtml(
            item.nom_de_formation || ""
          )}</option>`
      )
      .join("");

    optionSelect.innerHTML = optionItems
      .map(
        (item) =>
          `<option value="${escapeHtml(item.id_option)}">${escapeHtml(item.id_option)} — ${escapeHtml(
            item.nom_option || ""
          )}</option>`
      )
      .join("");
  }

  function renderPlanningOptionBackTable() {
    const tbody = document.getElementById("planning-option-back-table-body");
    if (!tbody) {
      return;
    }

    const planningItems = loadPlanning();
    const optionItems = loadOptionn();
    const links = loadPlanningOption();

    tbody.innerHTML = links
      .map((link) => {
        const planning = planningItems.find((item) => Number(item.id_planning) === Number(link.id_planning));
        const option = optionItems.find((item) => Number(item.id_option) === Number(link.id_option));

        return `
          <tr>
            <td>${escapeHtml(link.id_planning)}</td>
            <td>${escapeHtml(planning ? planning.nom_de_formation : "(planning supprimé)")}</td>
            <td>${escapeHtml(link.id_option)}</td>
            <td>${escapeHtml(option ? option.nom_option : "(option supprimée)")}</td>
            <td>
              <button
                type="button"
                class="btn-sm btn-sm--edit"
                data-po-action="edit"
                data-id-planning="${escapeHtml(link.id_planning)}"
                data-id-option="${escapeHtml(link.id_option)}"
              >
                Modifier
              </button>
              <button
                type="button"
                class="btn-sm btn-sm--danger"
                data-po-action="delete"
                data-id-planning="${escapeHtml(link.id_planning)}"
                data-id-option="${escapeHtml(link.id_option)}"
              >
                Supprimer
              </button>
            </td>
          </tr>
        `;
      })
      .join("");

    tbody.querySelectorAll("button[data-po-action='edit']").forEach((button) => {
      button.addEventListener("click", function () {
        const idPlanning = this.getAttribute("data-id-planning");
        const idOption = this.getAttribute("data-id-option");

        const planningSelect = document.getElementById("po_id_planning");
        const optionSelect = document.getElementById("po_id_option");
        const originalPlanning = document.getElementById("po_original_id_planning");
        const originalOption = document.getElementById("po_original_id_option");

        if (planningSelect) {
          planningSelect.value = idPlanning;
        }
        if (optionSelect) {
          optionSelect.value = idOption;
        }
        if (originalPlanning) {
          originalPlanning.value = idPlanning;
        }
        if (originalOption) {
          originalOption.value = idOption;
        }
      });
    });

    tbody.querySelectorAll("button[data-po-action='delete']").forEach((button) => {
      button.addEventListener("click", function () {
        const idPlanning = Number(this.getAttribute("data-id-planning"));
        const idOption = Number(this.getAttribute("data-id-option"));
        const confirmed = window.confirm("Supprimer cette liaison ?");
        if (!confirmed) {
          return;
        }

        const nextLinks = loadPlanningOption().filter(
          (link) => !(Number(link.id_planning) === idPlanning && Number(link.id_option) === idOption)
        );
        savePlanningOption(nextLinks);
        renderPlanningOptionBackTable();
        clearPlanningOptionForm();
      });
    });
  }

  function bindPlanningOptionBackForm() {
    const form = document.getElementById("planning-option-back-form");
    if (!form) {
      return;
    }

    const planningSelect = document.getElementById("po_id_planning");
    const optionSelect = document.getElementById("po_id_option");
    const originalPlanning = document.getElementById("po_original_id_planning");
    const originalOption = document.getElementById("po_original_id_option");
    const cancelButton = document.getElementById("po-cancel-btn");

    form.addEventListener("submit", function (event) {
      event.preventDefault();

      const idPlanning = Number(planningSelect.value);
      const idOption = Number(optionSelect.value);
      const oldPlanning = Number(originalPlanning.value);
      const oldOption = Number(originalOption.value);

      if (!idPlanning || !idOption) {
        window.alert("Sélectionnez id_planning et id_option.");
        return;
      }

      const links = loadPlanningOption();
      const isEdit = oldPlanning && oldOption;
      const hasDuplicate = links.some((link) => {
        const samePair = Number(link.id_planning) === idPlanning && Number(link.id_option) === idOption;
        const isCurrentEdited =
          Number(link.id_planning) === oldPlanning && Number(link.id_option) === oldOption;
        return samePair && (!isEdit || !isCurrentEdited);
      });

      if (hasDuplicate) {
        window.alert("Cette liaison existe déjà.");
        return;
      }

      let nextLinks;
      if (isEdit) {
        nextLinks = links.map((link) =>
          Number(link.id_planning) === oldPlanning && Number(link.id_option) === oldOption
            ? { id_planning: idPlanning, id_option: idOption }
            : link
        );
      } else {
        nextLinks = [...links, { id_planning: idPlanning, id_option: idOption }];
      }

      savePlanningOption(nextLinks);
      renderPlanningOptionBackTable();
      clearPlanningOptionForm();
    });

    if (cancelButton) {
      cancelButton.addEventListener("click", function () {
        clearPlanningOptionForm();
      });
    }
  }

  function renderPlanningBackTable() {
    const tbody = document.getElementById("planning-back-table-body");
    if (!tbody) {
      return;
    }

    const items = loadPlanning();
    tbody.innerHTML = items
      .map(
        (item) => `
          <tr>
            <td>${escapeHtml(item.id_planning)}</td>
            <td>${escapeHtml(item.nom_de_formation || "")}</td>
            <td>${escapeHtml(item.date_debut || "")}</td>
            <td>${escapeHtml(item.date_fin || "")}</td>
            <td>${escapeHtml(item.TYPE || "")}</td>
            <td>${escapeHtml(calculateDurationDays(item.date_debut, item.date_fin))}</td>
            <td>${escapeHtml(calculatePlanningStatus(item.date_debut, item.date_fin))}</td>
            <td>
              <button type="button" class="btn-sm btn-sm--edit" data-planning-action="edit" data-id="${escapeHtml(item.id_planning)}">Modifier</button>
              <button type="button" class="btn-sm btn-sm--danger" data-planning-action="delete" data-id="${escapeHtml(item.id_planning)}">Supprimer</button>
            </td>
          </tr>
        `
      )
      .join("");

    tbody.querySelectorAll("button[data-planning-action='edit']").forEach((button) => {
      button.addEventListener("click", function () {
        const id = Number(this.getAttribute("data-id"));
        const item = loadPlanning().find((entry) => Number(entry.id_planning) === id);
        if (!item) {
          return;
        }

        document.getElementById("id_planning").value = String(item.id_planning);
        document.getElementById("nom_de_formation").value = item.nom_de_formation || "";
        document.getElementById("date_debut").value = item.date_debut || "";
        document.getElementById("date_fin").value = item.date_fin || "";
        document.getElementById("TYPE").value = item.TYPE || "";
      });
    });

    tbody.querySelectorAll("button[data-planning-action='delete']").forEach((button) => {
      button.addEventListener("click", function () {
        const id = Number(this.getAttribute("data-id"));
        const confirmed = window.confirm("Supprimer ce planning ?");
        if (!confirmed) {
          return;
        }

        const next = loadPlanning().filter((item) => Number(item.id_planning) !== id);
        savePlanning(next);
        const nextLinks = loadPlanningOption().filter((link) => Number(link.id_planning) !== id);
        savePlanningOption(nextLinks);
        renderPlanningBackTable();
        renderPlanningOptionSelectors();
        renderPlanningOptionBackTable();
        clearPlanningForm();
      });
    });
  }

  function bindPlanningBackForm() {
    const form = document.getElementById("planning-back-form");
    if (!form) {
      return;
    }

    const idInput = document.getElementById("id_planning");
    const nomInput = document.getElementById("nom_de_formation");
    const dateDebutInput = document.getElementById("date_debut");
    const dateFinInput = document.getElementById("date_fin");
    const typeInput = document.getElementById("TYPE");
    const cancelButton = document.getElementById("planning-cancel-btn");

    form.addEventListener("submit", function (event) {
      event.preventDefault();

      const nom = nomInput.value.trim();
      const dateDebut = dateDebutInput.value;
      const dateFin = dateFinInput.value;
      const type = typeInput.value.trim();

      if (!nom || !dateDebut || !dateFin || !type) {
        window.alert("Tous les champs planning sont obligatoires.");
        return;
      }

      if (new Date(dateFin) < new Date(dateDebut)) {
        window.alert("date_fin doit être supérieure ou égale à date_debut.");
        return;
      }

      const items = loadPlanning();
      const idValue = Number(idInput.value);

      if (idValue) {
        const updated = items.map((item) =>
          Number(item.id_planning) === idValue
            ? {
                id_planning: idValue,
                nom_de_formation: nom,
                date_debut: dateDebut,
                date_fin: dateFin,
                TYPE: type,
              }
            : item
        );
        savePlanning(updated);
      } else {
        items.push({
          id_planning: getNextNumericId(items, "id_planning"),
          nom_de_formation: nom,
          date_debut: dateDebut,
          date_fin: dateFin,
          TYPE: type,
        });
        savePlanning(items);
      }

      renderPlanningBackTable();
      renderPlanningOptionSelectors();
      renderPlanningOptionBackTable();
      clearPlanningForm();
    });

    if (cancelButton) {
      cancelButton.addEventListener("click", function () {
        clearPlanningForm();
      });
    }
  }

  function renderFrontCards() {
    const grid = document.getElementById("optionn-front-grid");
    if (!grid) {
      return;
    }

    const items = loadOptionn();
    grid.innerHTML = items
      .map(
        (item) => `
          <article class="card">
            <span class="badge">id_option: ${escapeHtml(item.id_option)}</span>
            <h2 style="margin: 0.75rem 0 0.5rem; font-size: 1.15rem">nom_option: ${escapeHtml(item.nom_option || "")}</h2>
            <p style="margin: 0 0 1rem; color: #64748b; font-size: 0.9rem">
              specialite: ${escapeHtml(item.specialite || "")} · description: ${escapeHtml(item.description || "")}
            </p>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap">
              <a class="btn btn--primary" href="formation-detail.html?id=${escapeHtml(item.id_option)}">Voir le détail</a>
            </div>
          </article>
        `
      )
      .join("");
  }

  function loadFrontItemInForm(id) {
    const form = document.getElementById("optionn-front-form");
    if (!form) {
      return;
    }
    const item = loadOptionn().find((entry) => Number(entry.id_option) === Number(id));
    if (!item) {
      return;
    }

    document.getElementById("front_id_option").value = String(item.id_option);
    document.getElementById("front_nom_option").value = item.nom_option || "";
    document.getElementById("front_specialite").value = item.specialite || "";
    document.getElementById("front_description").value = item.description || "";

    const title = document.getElementById("front-form-title");
    if (title) {
      title.textContent = `Modifier option #${item.id_option}`;
    }
  }

  function clearFrontForm() {
    const form = document.getElementById("optionn-front-form");
    if (!form) {
      return;
    }
    form.reset();
    document.getElementById("front_id_option").value = "";
    const title = document.getElementById("front-form-title");
    if (title) {
      title.textContent = "Ajouter une option (CRUD FrontOffice)";
    }
  }

  function bindFrontForm() {
    const form = document.getElementById("optionn-front-form");
    if (!form) {
      return;
    }

    const cancelButton = document.getElementById("front-cancel-btn");

    form.addEventListener("submit", function (event) {
      event.preventDefault();

      const idValue = Number(document.getElementById("front_id_option").value);
      const nom = document.getElementById("front_nom_option").value.trim();
      const specialite = document.getElementById("front_specialite").value.trim();
      const description = document.getElementById("front_description").value.trim();

      if (!nom) {
        window.alert("Le champ nom_option est obligatoire.");
        return;
      }

      const items = loadOptionn();

      if (idValue) {
        const updated = items.map((item) =>
          Number(item.id_option) === idValue
            ? {
                id_option: idValue,
                nom_option: nom,
                specialite,
                description,
              }
            : item
        );
        saveOptionn(updated);
      } else {
        items.push({
          id_option: getNextId(items),
          nom_option: nom,
          specialite,
          description,
        });
        saveOptionn(items);
      }

      renderFrontCards();
      clearFrontForm();
    });

    if (cancelButton) {
      cancelButton.addEventListener("click", function () {
        clearFrontForm();
      });
    }
  }

  function renderFrontDetail() {
    const detailContainer = document.getElementById("optionn-detail-current");
    if (!detailContainer) {
      return;
    }

    const params = getParams();
    const id = Number(params.get("id"));
    const fallback = loadOptionn()[0];
    const current = loadOptionn().find((item) => Number(item.id_option) === id) || fallback;

    if (!current) {
      detailContainer.innerHTML = "<p>Aucune option disponible.</p>";
      return;
    }

    detailContainer.innerHTML = `
      <li>
        <div>
          <strong>id_option: ${escapeHtml(current.id_option)}</strong> — nom_option: ${escapeHtml(current.nom_option || "")}
          <div style="font-size: 0.85rem; color: #64748b">specialite: ${escapeHtml(current.specialite || "")} · description: ${escapeHtml(current.description || "")}</div>
        </div>
        <span class="badge">Actif</span>
      </li>
    `;
  }

  document.addEventListener("DOMContentLoaded", function () {
    renderBackTable();
    bindBackEditorForm();
    bindOptionnBackInlineForm();
    renderPlanningBackTable();
    bindPlanningBackForm();
    renderPlanningOptionSelectors();
    renderPlanningOptionBackTable();
    bindPlanningOptionBackForm();
    renderFrontCards();
    renderFrontDetail();
  });
})();
