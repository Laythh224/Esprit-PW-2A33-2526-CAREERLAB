(function () {
  function showFlashAlert(nodeId) {
    const flashNode = document.getElementById(nodeId);
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

  function getTodayIsoDate() {
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

  function bindFormationForm() {
    const form = document.getElementById('formation-form');
    if (!form) {
      return;
    }

    const oldNomInput = document.getElementById('old_nom_formation');
    const nomInput = document.getElementById('nom_formation');
    const specialiteInput = document.getElementById('specialite');
    const descriptionInput = document.getElementById('description');
    const niveauInput = document.getElementById('niveau');
    const nbPlaceInput = document.getElementById('nb_place');
    const cancelBtn = document.getElementById('formation-cancel-btn');
    const title = document.getElementById('formation-form-title');

    form.setAttribute('novalidate', 'novalidate');

    form.addEventListener('submit', function (event) {
      const nom = normalizeSpaces(nomInput.value);
      const specialite = normalizeSpaces(specialiteInput.value);
      const description = normalizeSpaces(descriptionInput.value);
      const niveau = normalizeSpaces(niveauInput.value);
      const nbPlace = Number(nbPlaceInput.value);

      if (!nom || !specialite || !description || !niveau || !nbPlaceInput.value.trim()) {
        event.preventDefault();
        window.alert('Tous les champs formation sont obligatoires.');
        return;
      }

      if (!Number.isInteger(nbPlace) || nbPlace <= 0) {
        event.preventDefault();
        window.alert('nb_place doit etre un entier positif.');
        return;
      }

      nomInput.value = nom;
      specialiteInput.value = specialite;
      descriptionInput.value = description;
      niveauInput.value = niveau;
    });

    document.querySelectorAll('.formation-edit-btn').forEach((button) => {
      button.addEventListener('click', function () {
        oldNomInput.value = this.dataset.nom || '';
        nomInput.value = this.dataset.nom || '';
        specialiteInput.value = this.dataset.specialite || '';
        descriptionInput.value = this.dataset.description || '';
        niveauInput.value = this.dataset.niveau || '';
        nbPlaceInput.value = this.dataset.nbPlace || '';
        form.action = 'index.php?r=back/formations/update';
        if (title) {
          title.textContent = 'Modifier une formation';
        }
      });
    });

    if (cancelBtn) {
      cancelBtn.addEventListener('click', function () {
        oldNomInput.value = '';
        nomInput.value = '';
        specialiteInput.value = '';
        descriptionInput.value = '';
        niveauInput.value = '';
        nbPlaceInput.value = '';
        form.action = 'index.php?r=back/formations/store';
        if (title) {
          title.textContent = 'Ajouter une formation';
        }
      });
    }
  }

  function toggleSessionFields() {
    const form = document.getElementById('session-form');
    const typeInput = document.getElementById('session_type');
    if (!form || !typeInput) {
      return;
    }

    const isOnline = typeInput.value === 'online';
    const isPresentiel = typeInput.value === 'presentiel';

    form.querySelectorAll('.js-online-field').forEach((node) => {
      node.classList.toggle('is-visible', isOnline);
    });

    form.querySelectorAll('.js-presentiel-field').forEach((node) => {
      node.classList.toggle('is-visible', isPresentiel);
    });
  }

  function bindSessionForm() {
    const form = document.getElementById('session-form');
    if (!form) {
      return;
    }

    const idInput = document.getElementById('session_id');
    const nomFormationInput = document.getElementById('nom_formation_select');
    const typeInput = document.getElementById('session_type');
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');
    const lienInput = document.getElementById('lien');
    const dureeOnlineInput = document.getElementById('duree_online');
    const adresseInput = document.getElementById('adresse');
    const salleInput = document.getElementById('salle');
    const dureePresentielInput = document.getElementById('duree_presentiel');
    const title = document.getElementById('session-form-title');
    const cancelBtn = document.getElementById('session-cancel-btn');

    if (!typeInput) {
      return;
    }

    form.setAttribute('novalidate', 'novalidate');
    typeInput.addEventListener('change', toggleSessionFields);
    typeInput.addEventListener('input', toggleSessionFields);
    toggleSessionFields();

    form.addEventListener('submit', function (event) {
      const type = typeInput.value;
      const nomFormation = nomFormationInput.value;
      const dateDebut = normalizeSpaces(dateDebutInput.value);
      const dateFin = normalizeSpaces(dateFinInput.value);

      if (!nomFormation || !type) {
        event.preventDefault();
        window.alert('nom_formation et type sont obligatoires.');
        return;
      }

      if (!dateDebut || !dateFin) {
        event.preventDefault();
        window.alert('date_debut et date_fin sont obligatoires.');
        return;
      }

      if (!isStrictIsoDate(dateDebut) || !isStrictIsoDate(dateFin)) {
        event.preventDefault();
        window.alert('Les dates doivent respecter le format YYYY-MM-DD.');
        return;
      }

      if (dateDebut < getTodayIsoDate()) {
        event.preventDefault();
        window.alert('date_debut ne peut pas etre une date passee.');
        return;
      }

      if (!isDateAfter(dateFin, dateDebut)) {
        event.preventDefault();
        window.alert('date_fin doit etre au moins 1 jour apres date_debut.');
        return;
      }

      if (type === 'online') {
        const lienValue = lienInput.value.trim();
        const dureeOnlineValue = Number(dureeOnlineInput.value);

        if (!lienValue || !dureeOnlineInput.value.trim()) {
          event.preventDefault();
          window.alert('lien et duree_online sont obligatoires pour online.');
          return;
        }

        try {
          // eslint-disable-next-line no-new
          new URL(lienValue);
        } catch (_error) {
          event.preventDefault();
          window.alert('lien doit etre une URL valide.');
          return;
        }

        if (!Number.isInteger(dureeOnlineValue) || dureeOnlineValue <= 0) {
          event.preventDefault();
          window.alert('duree_online doit etre un entier positif.');
          return;
        }
      } else if (type === 'presentiel') {
        const dureePresentielValue = Number(dureePresentielInput.value);

        if (!adresseInput.value.trim() || !salleInput.value.trim() || !dureePresentielInput.value.trim()) {
          event.preventDefault();
          window.alert('adresse, salle et duree_presentiel sont obligatoires pour presentiel.');
          return;
        }

        if (!Number.isInteger(dureePresentielValue) || dureePresentielValue <= 0) {
          event.preventDefault();
          window.alert('duree_presentiel doit etre un entier positif.');
          return;
        }
      } else {
        event.preventDefault();
        window.alert('Type invalide.');
        return;
      }
    });

    document.querySelectorAll('.session-edit-btn').forEach((button) => {
      button.addEventListener('click', function () {
        idInput.value = this.dataset.id || '';
        nomFormationInput.value = this.dataset.nomFormation || '';
        typeInput.value = this.dataset.type || '';
        dateDebutInput.value = this.dataset.dateDebut || '';
        dateFinInput.value = this.dataset.dateFin || '';
        lienInput.value = this.dataset.lien || '';
        dureeOnlineInput.value = this.dataset.dureeOnline || '';
        adresseInput.value = this.dataset.adresse || '';
        salleInput.value = this.dataset.salle || '';
        dureePresentielInput.value = this.dataset.dureePresentiel || '';
        form.action = 'index.php?r=back/sessions/update';
        toggleSessionFields();
        if (title) {
          title.textContent = 'Modifier une session';
        }
      });
    });

    if (cancelBtn) {
      cancelBtn.addEventListener('click', function () {
        idInput.value = '';
        nomFormationInput.value = '';
        typeInput.value = '';
        dateDebutInput.value = '';
        dateFinInput.value = '';
        lienInput.value = '';
        dureeOnlineInput.value = '';
        adresseInput.value = '';
        salleInput.value = '';
        dureePresentielInput.value = '';
        form.action = 'index.php?r=back/sessions/store';
        toggleSessionFields();
        if (title) {
          title.textContent = 'Ajouter une session';
        }
      });
    }
  }

  function normalizeSearchText(value) {
    return String(value)
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .trim();
  }

  function toTitleCase(value) {
    const text = String(value || '').trim();
    if (!text) {
      return 'Non renseigne';
    }
    return text
      .split(/\s+/)
      .map((word) => (word ? word.charAt(0).toUpperCase() + word.slice(1) : ''))
      .join(' ');
  }

  function renderStatsCircles(container, rows, statsField, presetLabels) {
    if (!container) {
      return;
    }

    const total = rows.length;
    const hasPreset = Array.isArray(presetLabels) && presetLabels.length > 0;

    if (total === 0 && !hasPreset) {
      container.innerHTML = '<p class="page-subtitle">Aucune donnee pour les statistiques.</p>';
      return;
    }

    const counts = new Map();
    rows.forEach((row) => {
      const rawLabel = String(row.dataset[`${statsField}Label`] || row.dataset[statsField] || '').trim();
      const key = normalizeSearchText(rawLabel) || 'non renseigne';
      const previous = counts.get(key);

      if (previous) {
        previous.count += 1;
      } else {
        counts.set(key, {
          label: rawLabel || 'Non renseigne',
          count: 1,
        });
      }
    });

    if (hasPreset) {
      presetLabels.forEach((label) => {
        const key = normalizeSearchText(label) || 'non renseigne';
        if (!counts.has(key)) {
          counts.set(key, { label, count: 0 });
        }
      });
    }

    let orderedEntries;
    if (hasPreset) {
      orderedEntries = presetLabels.map((label) => {
        const key = normalizeSearchText(label) || 'non renseigne';
        const stat = counts.get(key);
        return [key, stat || { label, count: 0 }];
      });
    } else {
      orderedEntries = Array.from(counts.entries())
        .sort((a, b) => b[1].count - a[1].count)
        .slice(0, 6);
    }

    const circumference = 251;
    container.innerHTML = orderedEntries
      .map(([, stat]) => {
        const count = stat.count;
        const percent = total === 0 ? 0 : Math.round((count * 100) / total);
        const offset = circumference - (circumference * percent) / 100;
        return `
          <article class="stats-circle">
            <div class="stats-circle__chart">
              <svg class="stats-circle__svg" viewBox="0 0 100 100" aria-hidden="true">
                <circle class="stats-circle__track" cx="50" cy="50" r="40"></circle>
                <circle class="stats-circle__bar" cx="50" cy="50" r="40" style="--target-offset: ${offset};"></circle>
              </svg>
              <div class="stats-circle__value">${count} (${percent}%)</div>
            </div>
            <div class="stats-circle__label">${toTitleCase(stat.label)}</div>
          </article>
        `;
      })
      .join('');

    requestAnimationFrame(() => {
      container.querySelectorAll('.stats-circle__bar').forEach((node) => node.classList.add('is-animated'));
    });
  }

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  async function imageUrlToDataUrl(imageUrl) {
    try {
      const response = await fetch(imageUrl);
      if (!response.ok) {
        return '';
      }

      const blob = await response.blob();
      return await new Promise((resolve) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(typeof reader.result === 'string' ? reader.result : '');
        reader.onerror = () => resolve('');
        reader.readAsDataURL(blob);
      });
    } catch (_error) {
      return '';
    }
  }

  async function exportWordDocument(title, table, rows, logoUrl) {
    const headers = Array.from(table.querySelectorAll('thead th'));
    const columnIndexes = headers
      .map((th, index) => ({ text: normalizeSearchText(th.textContent || ''), index }))
      .filter((item) => item.text !== 'actions')
      .map((item) => item.index);

    const thead = columnIndexes
      .map((index) => `<th>${escapeHtml(headers[index].textContent || '')}</th>`)
      .join('');

    const tbody = rows
      .map((row) => {
        const cells = Array.from(row.children);
        const columns = columnIndexes
          .map((index) => `<td>${escapeHtml(cells[index] ? cells[index].textContent || '' : '')}</td>`)
          .join('');
        return `<tr>${columns}</tr>`;
      })
      .join('');

    const logoDataUrl = await imageUrlToDataUrl(logoUrl);
    const logoHtml = logoDataUrl ? `<img src="${logoDataUrl}" alt="CareerLab logo" style="height: 56px; width: auto; margin-bottom: 10px;" />` : '';
    const now = new Date().toLocaleString('fr-FR');

    const documentHtml = `
      <!doctype html>
      <html lang="fr" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word">
      <head>
        <meta charset="utf-8">
        <title>${escapeHtml(title)} - Export Word</title>
        <style>
          body { font-family: Arial, sans-serif; margin: 24px; color: #1f2937; }
          h1 { margin: 0 0 8px; font-size: 22px; }
          .meta { margin: 0 0 14px; color: #475569; font-size: 12px; }
          table { width: 100%; border-collapse: collapse; font-size: 12px; }
          th, td { border: 1px solid #d1d5db; padding: 6px; text-align: left; vertical-align: top; }
          th { background: #f3f4f6; }
        </style>
      </head>
      <body>
        ${logoHtml}
        <h1>${escapeHtml(title)} - Base de donnees</h1>
        <p class="meta">Export du ${escapeHtml(now)}</p>
        <table>
          <thead><tr>${thead}</tr></thead>
          <tbody>${tbody}</tbody>
        </table>
      </body>
      </html>
    `;

    const blob = new Blob(['\ufeff', documentHtml], { type: 'application/msword' });
    const fileUrl = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = fileUrl;
    link.download = `${title.toLowerCase().replace(/\s+/g, '-')}-base-de-donnees.doc`;
    document.body.appendChild(link);
    link.click();
    link.remove();
    URL.revokeObjectURL(fileUrl);
  }

  function bindTableEnhancements(config) {
    const table = document.getElementById(config.tableId);
    const searchInput = document.getElementById(config.searchId);
    const sortInput = document.getElementById(config.sortId);
    const statsContainer = document.getElementById(config.statsId);
    const exportButton = document.getElementById(config.exportButtonId);

    if (!table || !searchInput || !sortInput || !statsContainer || !exportButton) {
      return;
    }

    const tbody = table.querySelector('tbody');
    if (!tbody) {
      return;
    }

    const allRows = Array.from(tbody.querySelectorAll('tr'));
    let visibleRows = allRows.slice();

    function rowMatchesSearch(row, query) {
      if (!query) {
        return true;
      }

      return config.searchFields.some((field) => {
        const value = normalizeSearchText(row.dataset[field] || '');
        return value.includes(query);
      });
    }

    function applyTableState() {
      const query = normalizeSearchText(searchInput.value);
      const sortDirection = sortInput.value === 'za' ? 'za' : 'az';

      visibleRows = allRows
        .filter((row) => rowMatchesSearch(row, query))
        .sort((a, b) => {
          const left = normalizeSearchText(a.dataset[config.sortField] || '');
          const right = normalizeSearchText(b.dataset[config.sortField] || '');
          if (left === right) {
            return 0;
          }
          const order = left > right ? 1 : -1;
          return sortDirection === 'za' ? -order : order;
        });

      tbody.innerHTML = '';
      visibleRows.forEach((row) => tbody.appendChild(row));
      renderStatsCircles(statsContainer, visibleRows, config.statsField, config.statsPresetLabels);
    }

    searchInput.addEventListener('input', applyTableState);
    sortInput.addEventListener('change', applyTableState);
    exportButton.addEventListener('click', async function () {
      await exportWordDocument(config.title, table, allRows, config.logoUrl);
    });

    applyTableState();
  }

  document.addEventListener('DOMContentLoaded', function () {
    showFlashAlert('formation-flash-message');
    showFlashAlert('session-flash-message');
    bindFormationForm();
    bindSessionForm();
    bindTableEnhancements({
      title: 'Formations',
      tableId: 'formations-table',
      searchId: 'formations-search',
      sortId: 'formations-sort',
      statsId: 'formations-stats',
      exportButtonId: 'formations-word-btn',
      logoUrl: '/careerlabb/e-learning/View/assets/img/careerlab-logo.png',
      searchFields: ['name', 'specialite'],
      sortField: 'name',
      statsField: 'specialite',
    });
    bindTableEnhancements({
      title: 'Sessions',
      tableId: 'sessions-table',
      searchId: 'sessions-search',
      sortId: 'sessions-sort',
      statsId: 'sessions-stats',
      exportButtonId: 'sessions-word-btn',
      logoUrl: '/careerlabb/e-learning/View/assets/img/careerlab-logo.png',
      searchFields: ['name', 'type', 'specialite'],
      sortField: 'name',
      statsField: 'type',
      statsPresetLabels: ['En ligne', 'Présentiel'],
    });
  });
})();
