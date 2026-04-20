(function () {
  function showOptionFlashAlert() {
    const flashNode = document.getElementById('option-flash-message');
    if (!flashNode) {
      return;
    }

    const message = (flashNode.dataset.message || '').trim();
    if (!message) {
      return;
    }

    window.alert(message);
  }

  function showPlanningOptionFlashAlert() {
    const flashNode = document.getElementById('planning-option-flash-message');
    if (!flashNode) {
      return;
    }

    const message = (flashNode.dataset.message || '').trim();
    if (!message) {
      return;
    }

    window.alert(message);
  }

  function normalizeSpaces(value) {
    return String(value).trim().replace(/\s+/g, ' ');
  }

  function isValidText(value, minLen, maxLen) {
    const pattern = /^[\p{L}\p{N}\s'’\-.,()]+$/u;
    return value.length >= minLen && value.length <= maxLen && pattern.test(value);
  }

  function isStrictIsoDate(value) {
    if (!/^\d{4}-\d{2}-\d{2}$/.test(value)) {
      return false;
    }

    const date = new Date(value + 'T00:00:00');
    if (Number.isNaN(date.getTime())) {
      return false;
    }

    const year = String(date.getFullYear()).padStart(4, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}` === value;
  }

  function getMinimumPlanningDate() {
    const today = new Date();
    const minDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());
    const year = String(minDate.getFullYear()).padStart(4, '0');
    const month = String(minDate.getMonth() + 1).padStart(2, '0');
    const day = String(minDate.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  function isDateAfter(value, minimumValue) {
    const valueDate = new Date(value + 'T00:00:00');
    const minimumDate = new Date(minimumValue + 'T00:00:00');
    return valueDate > minimumDate;
  }

  function bindOptionForm() {
    const form = document.getElementById('option-form');
    if (!form) {
      return;
    }

    const idInput = document.getElementById('id_option');
    const nomInput = document.getElementById('nom_option');
    const specialiteInput = document.getElementById('specialite');
    const descriptionInput = document.getElementById('description');
    const cancelBtn = document.getElementById('cancel-edit-btn');
    const title = document.getElementById('option-form-title');

    form.setAttribute('novalidate', 'novalidate');

    form.addEventListener('submit', function (event) {
      const nom = normalizeSpaces(nomInput.value);
      const specialite = normalizeSpaces(specialiteInput.value);
      const description = normalizeSpaces(descriptionInput.value);

      if (!nom) {
        event.preventDefault();
        window.alert('Le champ nom_option est obligatoire.');
        return;
      }

      if (!specialite) {
        event.preventDefault();
        window.alert('Le champ specialite est obligatoire.');
        return;
      }

      if (!description) {
        event.preventDefault();
        window.alert('Le champ description est obligatoire.');
        return;
      }

      if (!isValidText(nom, 2, 80)) {
        event.preventDefault();
        window.alert('Le champ nom_option doit contenir 2 a 80 caracteres valides.');
        return;
      }

      if (!isValidText(specialite, 2, 80)) {
        event.preventDefault();
        window.alert('Le champ specialite doit contenir 2 a 80 caracteres valides.');
        return;
      }

      if (description.length < 5 || description.length > 300) {
        event.preventDefault();
        window.alert('Le champ description doit contenir 5 a 300 caracteres.');
        return;
      }

      if (!isValidText(description, 5, 300)) {
        event.preventDefault();
        window.alert('Le champ description contient des caracteres non valides.');
        return;
      }

      nomInput.value = nom;
      specialiteInput.value = specialite;
      descriptionInput.value = description;
    });

    document.querySelectorAll('.option-edit-btn').forEach((button) => {
      button.addEventListener('click', function () {
        idInput.value = this.dataset.id || '';
        nomInput.value = this.dataset.nom || '';
        specialiteInput.value = this.dataset.specialite || '';
        descriptionInput.value = this.dataset.description || '';
        form.action = 'index.php?r=back/options/update';
        if (title) {
          title.textContent = 'Modifier une option';
        }
      });
    });

    if (cancelBtn) {
      cancelBtn.addEventListener('click', function () {
        idInput.value = '';
        nomInput.value = '';
        specialiteInput.value = '';
        descriptionInput.value = '';
        form.action = 'index.php?r=back/options/store';
        if (title) {
          title.textContent = 'Ajouter une option';
        }
      });
    }
  }

  function bindPlanningForm() {
    const form = document.getElementById('planning-form') || document.getElementById('formPlanning');
    if (!form) {
      return;
    }

    const idInput = document.getElementById('id_planning');
    const nomInput = document.getElementById('nom_de_formation');
    const debutInput = document.getElementById('date_debut');
    const finInput = document.getElementById('date_fin');
    const typeInput = document.getElementById('TYPE');
    const title = document.getElementById('planning-form-title');
    const cancelBtn = document.getElementById('planning-cancel-btn');

    function showError(message) {
      window.alert(message);
    }

    form.setAttribute('novalidate', 'novalidate');

    form.addEventListener('submit', function (event) {
      const nom = normalizeSpaces(nomInput.value);
      const dateDebut = normalizeSpaces(debutInput.value);
      const dateFin = normalizeSpaces(finInput.value);
      const type = normalizeSpaces(typeInput.value);
      const minimumDate = getMinimumPlanningDate();

      if (!nom || !dateDebut || !dateFin || !type) {
        event.preventDefault();
        showError('Les champs nom_de_formation, date_debut, date_fin et TYPE sont obligatoires.');
        return;
      }

      if (!isValidText(nom, 3, 120)) {
        event.preventDefault();
        showError('Le champ nom_de_formation doit contenir 3 a 120 caracteres valides.');
        return;
      }

      if (!isValidText(type, 2, 40)) {
        event.preventDefault();
        showError('Le champ TYPE doit contenir 2 a 40 caracteres valides.');
        return;
      }

      if (!isStrictIsoDate(dateDebut) || !isStrictIsoDate(dateFin)) {
        event.preventDefault();
        showError('Les champs date_debut et date_fin doivent respecter le format YYYY-MM-DD.');
        return;
      }

      if (!isDateAfter(dateDebut, minimumDate)) {
        event.preventDefault();
        showError(`Le champ date_debut doit etre strictement superieur a la date du jour (${minimumDate}).`);
        return;
      }

      if (!isDateAfter(dateFin, dateDebut)) {
        event.preventDefault();
        showError('Le champ date_fin doit etre strictement superieur au champ date_debut.');
        return;
      }

      nomInput.value = nom;
      debutInput.value = dateDebut;
      finInput.value = dateFin;
      typeInput.value = type;
    });

    document.querySelectorAll('.planning-edit-btn').forEach((button) => {
      button.addEventListener('click', function () {
        idInput.value = this.dataset.id || '';
        nomInput.value = this.dataset.nom || '';
        debutInput.value = this.dataset.debut || '';
        finInput.value = this.dataset.fin || '';
        typeInput.value = this.dataset.type || '';
        form.action = 'index.php?r=back/planning/update';
        if (title) {
          title.textContent = 'Modifier un planning';
        }
      });
    });

    if (cancelBtn) {
      cancelBtn.addEventListener('click', function () {
        idInput.value = '';
        nomInput.value = '';
        debutInput.value = '';
        finInput.value = '';
        typeInput.value = '';
        form.action = 'index.php?r=back/planning/store';
        if (title) {
          title.textContent = 'Ajouter un planning';
        }
      });
    }
  }

  function bindPlanningOptionForm() {
    const form = document.getElementById('planning-option-form');
    if (!form) {
      return;
    }

    const planningInput = document.getElementById('id_planning');
    const optionInput = document.getElementById('id_option');

    form.setAttribute('novalidate', 'novalidate');

    form.addEventListener('submit', function (event) {
      const idPlanning = Number(planningInput.value);
      const idOption = Number(optionInput.value);

      if (!idPlanning || !idOption) {
        event.preventDefault();
        window.alert('Les champs id_planning et id_option sont obligatoires.');
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    showOptionFlashAlert();
    showPlanningOptionFlashAlert();
    bindOptionForm();
    bindPlanningForm();
    bindPlanningOptionForm();
  });
})();