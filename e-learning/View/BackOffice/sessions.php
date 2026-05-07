<?php
$title = 'Sessions';
$active = 'sessions';
$oldInput = isset($oldInput) && is_array($oldInput) ? $oldInput : [];
$formationSpecialites = isset($formationSpecialites) && is_array($formationSpecialites) ? $formationSpecialites : [];
$isEditing = ($oldInput['_mode'] ?? '') === 'update';
$formAction = $isEditing ? 'index.php?r=back/sessions/update' : 'index.php?r=back/sessions/store';
$formTitle = $isEditing ? 'Modifier une session' : 'Ajouter une session';
$typeVal = (string) ($oldInput['type'] ?? '');
$onlineVisible = $typeVal === 'online';
$presentielVisible = $typeVal === 'presentiel';
require __DIR__ . '/../Layouts/back_header.php';
?>
<header class="back-topbar">
  <h1>Sessions</h1>
  <p>Planifiez les sessions et gerez leur disponibilite.</p>
</header>
<div class="back-content">
  <?php if (!empty($flash)): ?>
    <div id="session-flash-message" data-message="<?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>" hidden></div>
  <?php endif; ?>

  <div class="back-card">
    <h2 class="back-card__title" id="session-form-title"><?= htmlspecialchars($formTitle, ENT_QUOTES, 'UTF-8') ?></h2>
    <form method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8') ?>" id="session-form">
      <input type="hidden" name="id" id="session_id" value="<?= htmlspecialchars((string) ($oldInput['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
      <div class="form-grid">
        <div class="form-group">
          <label for="nom_formation_select">Formation</label>
          <select id="nom_formation_select" name="nom_formation" required>
            <option value="">Choisir une formation</option>
            <?php foreach ($formationChoices as $formationName): ?>
              <option value="<?= htmlspecialchars($formationName, ENT_QUOTES, 'UTF-8') ?>" <?= (($oldInput['nom_formation'] ?? '') === $formationName) ? 'selected' : '' ?>>
                <?= htmlspecialchars($formationName, ENT_QUOTES, 'UTF-8') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="session_type">Type</label>
          <select id="session_type" name="type" required>
            <option value="">Choisir un type</option>
            <option value="online" <?= (($oldInput['type'] ?? '') === 'online') ? 'selected' : '' ?>>En ligne</option>
            <option value="presentiel" <?= (($oldInput['type'] ?? '') === 'presentiel') ? 'selected' : '' ?>>Presentiel</option>
          </select>
        </div>
        <div class="form-group js-online-field<?= $onlineVisible ? ' is-visible' : '' ?>">
          <label for="lien">lien</label>
          <input type="url" name="lien" id="lien" value="<?= htmlspecialchars((string) ($oldInput['lien'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
        </div>
        <div class="form-group js-online-field<?= $onlineVisible ? ' is-visible' : '' ?>">
          <label for="duree_online">Duree en ligne (heures)</label>
          <input type="number" min="1" name="duree_online" id="duree_online" value="<?= htmlspecialchars((string) ($oldInput['duree_online'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
        </div>
        <div class="form-group js-presentiel-field<?= $presentielVisible ? ' is-visible' : '' ?>">
          <label for="adresse">Adresse</label>
          <input type="text" name="adresse" id="adresse" value="<?= htmlspecialchars((string) ($oldInput['adresse'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
        </div>
        <div class="form-group js-presentiel-field<?= $presentielVisible ? ' is-visible' : '' ?>">
          <label for="salle">Salle</label>
          <input type="text" name="salle" id="salle" value="<?= htmlspecialchars((string) ($oldInput['salle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
        </div>
        <div class="form-group js-presentiel-field<?= $presentielVisible ? ' is-visible' : '' ?>">
          <label for="duree_presentiel">Duree presentiel (heures)</label>
          <input type="number" min="1" name="duree_presentiel" id="duree_presentiel" value="<?= htmlspecialchars((string) ($oldInput['duree_presentiel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
        </div>
        <div class="form-group">
          <label for="date_debut">Date debut</label>
          <input type="date" name="date_debut" id="date_debut" value="<?= htmlspecialchars((string) ($oldInput['date_debut'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required />
        </div>
        <div class="form-group">
          <label for="date_fin">Date fin</label>
          <input type="date" name="date_fin" id="date_fin" value="<?= htmlspecialchars((string) ($oldInput['date_fin'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required />
        </div>
        <div class="form-group">
          <label for="session_nb_place">Nombre de places</label>
          <input type="number" min="1" name="nb_place" id="session_nb_place" value="<?= htmlspecialchars((string) ($oldInput['nb_place'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required />
        </div>
      </div>
      <div class="actions-row">
        <button type="submit" class="btn-primary">Enregistrer</button>
        <button type="button" class="btn-secondary" id="session-cancel-btn">Annuler</button>
      </div>
    </form>
  </div>

  <div class="back-card">
    <div class="table-tools">
      <div class="table-tools__group">
        <label for="sessions-search">Recherche</label>
        <input type="search" id="sessions-search" class="table-tools__input" placeholder="Rechercher formation, type ou specialite..." />
      </div>
      <div class="table-tools__group">
        <label for="sessions-sort">Tri</label>
        <select id="sessions-sort" class="table-tools__select">
          <option value="az">A-Z</option>
          <option value="za">Z-A</option>
        </select>
      </div>
      <p class="table-tools__result" id="sessions-result-count"></p>
      <button type="button" class="btn-secondary" id="sessions-word-btn">Exporter Word</button>
    </div>
    <div id="sessions-stats" class="stats-circles stats-circles--session-type"></div>
    <div class="table-wrap">
      <table class="data-table" id="sessions-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Formation</th>
            <th>Type</th>
            <th>Date debut</th>
            <th>Date fin</th>
            <th>Lien</th>
            <th>Duree en ligne</th>
            <th>Adresse</th>
            <th>Salle</th>
            <th>Duree presentiel</th>
            <th>Places</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($sessions as $session): ?>
            <?php $specialite = (string) ($formationSpecialites[$session['nom_formation'] ?? ''] ?? ''); ?>
            <?php
              $typeRaw = (string) ($session['type'] ?? '');
              $typeLabel = $typeRaw === 'online' ? 'En ligne' : ($typeRaw === 'presentiel' ? 'Présentiel' : $typeRaw);
            ?>
            <tr
              data-name="<?= htmlspecialchars(strtolower((string) ($session['nom_formation'] ?? '')), ENT_QUOTES, 'UTF-8') ?>"
              data-type="<?= htmlspecialchars(strtolower($typeRaw), ENT_QUOTES, 'UTF-8') ?>"
              data-type-label="<?= htmlspecialchars($typeLabel, ENT_QUOTES, 'UTF-8') ?>"
              data-specialite="<?= htmlspecialchars(strtolower($specialite), ENT_QUOTES, 'UTF-8') ?>"
              data-specialite-label="<?= htmlspecialchars($specialite, ENT_QUOTES, 'UTF-8') ?>"
            >
              <td><?= (int) $session['id'] ?></td>
              <td><?= htmlspecialchars($session['nom_formation'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($session['type'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string) ($session['date_debut'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string) ($session['date_fin'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($session['lien'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string) ($session['duree_online'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($session['adresse'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($session['salle'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string) ($session['duree_presentiel'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= (int) ($session['nb_place'] ?? 0) ?></td>
              <td>
                <button
                  type="button"
                  class="btn-sm btn-sm--edit session-edit-btn"
                  data-id="<?= (int) $session['id'] ?>"
                  data-nom-formation="<?= htmlspecialchars($session['nom_formation'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-type="<?= htmlspecialchars($session['type'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-date-debut="<?= htmlspecialchars((string) ($session['date_debut'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                  data-date-fin="<?= htmlspecialchars((string) ($session['date_fin'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                  data-lien="<?= htmlspecialchars($session['lien'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-duree-online="<?= htmlspecialchars((string) ($session['duree_online'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                  data-adresse="<?= htmlspecialchars($session['adresse'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-salle="<?= htmlspecialchars($session['salle'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-duree-presentiel="<?= htmlspecialchars((string) ($session['duree_presentiel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                  data-nb-place="<?= (int) ($session['nb_place'] ?? 0) ?>"
                >
                  Modifier
                </button>
                <form method="post" action="index.php?r=back/sessions/delete" class="inline-form">
                  <input type="hidden" name="id" value="<?= (int) $session['id'] ?>" />
                  <button type="submit" class="btn-sm btn-sm--danger">Supprimer</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="/careerlabb/e-learning/View/assets/js/back-form-validation.js"></script>
<script>
(function () {
  function syncSessionTypeFields() {
    var form = document.getElementById('session-form');
    var sel = document.getElementById('session_type');
    if (!form || !sel) {
      return;
    }
    var v = sel.value;
    form.querySelectorAll('.js-online-field').forEach(function (el) {
      el.classList.toggle('is-visible', v === 'online');
    });
    form.querySelectorAll('.js-presentiel-field').forEach(function (el) {
      el.classList.toggle('is-visible', v === 'presentiel');
    });
  }
  document.addEventListener('DOMContentLoaded', function () {
    var sel = document.getElementById('session_type');
    if (!sel) {
      return;
    }
    sel.addEventListener('change', syncSessionTypeFields);
    sel.addEventListener('input', syncSessionTypeFields);
    syncSessionTypeFields();
  });
})();
</script>
<?php require __DIR__ . '/../Layouts/back_footer.php'; ?>
