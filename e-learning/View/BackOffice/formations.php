<?php
$title = 'Formations';
$active = 'formations';
$oldInput = isset($oldInput) && is_array($oldInput) ? $oldInput : [];
$isEditing = ($oldInput['_mode'] ?? '') === 'update';
$formAction = $isEditing ? 'index.php?r=back/formations/update' : 'index.php?r=back/formations/store';
$formTitle = $isEditing ? 'Modifier une formation' : 'Ajouter une formation';
require __DIR__ . '/../Layouts/back_header.php';
?>
<header class="back-topbar">
  <h1>Formations</h1>
</header>
<div class="back-content">
  <?php if (!empty($flash)): ?>
    <div id="formation-flash-message" data-message="<?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>" hidden></div>
  <?php endif; ?>

  <div class="back-card">
    <h2 class="back-card__title" id="formation-form-title"><?= htmlspecialchars($formTitle, ENT_QUOTES, 'UTF-8') ?></h2>
    <form method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8') ?>" id="formation-form">
      <input type="hidden" name="old_nom_formation" id="old_nom_formation" value="<?= htmlspecialchars((string) ($oldInput['old_nom_formation'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
      <div class="form-grid">
        <div class="form-group">
          <label for="nom_formation">nom_formation</label>
          <input type="text" name="nom_formation" id="nom_formation" value="<?= htmlspecialchars((string) ($oldInput['nom_formation'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required />
        </div>
        <div class="form-group">
          <label for="specialite">specialite</label>
          <input type="text" name="specialite" id="specialite" value="<?= htmlspecialchars((string) ($oldInput['specialite'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required />
        </div>
        <div class="form-group" style="grid-column: 1 / -1">
          <label for="description">description</label>
          <textarea name="description" id="description" required><?= htmlspecialchars((string) ($oldInput['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="form-group">
          <label for="niveau">niveau</label>
          <input type="text" name="niveau" id="niveau" value="<?= htmlspecialchars((string) ($oldInput['niveau'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required />
        </div>
        <div class="form-group">
          <label for="nb_place">nb_place</label>
          <input type="number" min="1" name="nb_place" id="nb_place" value="<?= htmlspecialchars((string) ($oldInput['nb_place'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required />
        </div>
      </div>
      <div class="actions-row">
        <button type="submit" class="btn-primary">Enregistrer</button>
        <button type="button" class="btn-secondary" id="formation-cancel-btn">Annuler</button>
      </div>
    </form>
  </div>

  <div class="back-card">
    <div class="table-tools">
      <div class="table-tools__group">
        <label for="formations-search">Recherche</label>
        <input type="search" id="formations-search" class="table-tools__input" placeholder="Rechercher formation ou specialite..." />
      </div>
      <div class="table-tools__group">
        <label for="formations-sort">Tri</label>
        <select id="formations-sort" class="table-tools__select">
          <option value="az">A-Z</option>
          <option value="za">Z-A</option>
        </select>
      </div>
      <button type="button" class="btn-secondary" id="formations-word-btn">Exporter Word</button>
    </div>
    <div id="formations-stats" class="stats-circles"></div>
    <div class="table-wrap">
      <table class="data-table" id="formations-table">
        <thead>
          <tr>
            <th>nom_formation</th>
            <th>specialite</th>
            <th>description</th>
            <th>niveau</th>
            <th>nb_place</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($formations as $formation): ?>
            <tr
              data-name="<?= htmlspecialchars(strtolower((string) ($formation['nom_formation'] ?? '')), ENT_QUOTES, 'UTF-8') ?>"
              data-specialite="<?= htmlspecialchars(strtolower((string) ($formation['specialite'] ?? '')), ENT_QUOTES, 'UTF-8') ?>"
              data-specialite-label="<?= htmlspecialchars((string) ($formation['specialite'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
            >
              <td><?= htmlspecialchars($formation['nom_formation'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($formation['specialite'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($formation['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($formation['niveau'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= (int) ($formation['nb_place'] ?? 0) ?></td>
              <td>
                <button
                  type="button"
                  class="btn-sm btn-sm--edit formation-edit-btn"
                  data-nom="<?= htmlspecialchars($formation['nom_formation'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-specialite="<?= htmlspecialchars($formation['specialite'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-description="<?= htmlspecialchars($formation['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-niveau="<?= htmlspecialchars($formation['niveau'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-nb-place="<?= (int) ($formation['nb_place'] ?? 0) ?>"
                >
                  Modifier
                </button>
                <form method="post" action="index.php?r=back/formations/delete" style="display:inline-block;">
                  <input type="hidden" name="nom_formation" value="<?= htmlspecialchars($formation['nom_formation'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
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
<?php require __DIR__ . '/../Layouts/back_footer.php'; ?>
