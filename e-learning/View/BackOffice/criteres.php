<?php
$title = 'Criteres';
$active = 'criteres';
$oldInput = isset($oldInput) && is_array($oldInput) ? $oldInput : [];
$formationSpecialites = isset($formationSpecialites) && is_array($formationSpecialites) ? $formationSpecialites : [];
$isEditing = ($oldInput['_mode'] ?? '') === 'update';
$formAction = $isEditing ? 'index.php?r=back/criteres/update' : 'index.php?r=back/criteres/store';
$formTitle = $isEditing ? 'Modifier un critere' : 'Ajouter un critere';
require __DIR__ . '/../Layouts/back_header.php';
?>
<header class="back-topbar">
  <h1>Criteres</h1>
</header>
<div class="back-content">
  <?php if (!empty($flash)): ?>
    <div id="critere-flash-message" data-message="<?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>" hidden></div>
  <?php endif; ?>

  <div class="back-card">
    <h2 class="back-card__title" id="critere-form-title"><?= htmlspecialchars($formTitle, ENT_QUOTES, 'UTF-8') ?></h2>
    <form method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8') ?>" id="critere-form">
      <input type="hidden" name="id" id="critere_id" value="<?= htmlspecialchars((string) ($oldInput['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
      <div class="form-grid">
        <div class="form-group">
          <label for="nom_formation_select">formation</label>
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
          <label for="type">type</label>
          <select id="type" name="type" required>
            <option value="">Choisir un type</option>
            <option value="online" <?= (($oldInput['type'] ?? '') === 'online') ? 'selected' : '' ?>>online</option>
            <option value="presentiel" <?= (($oldInput['type'] ?? '') === 'presentiel') ? 'selected' : '' ?>>presentiel</option>
          </select>
        </div>
        <div class="form-group js-online-field" style="display:none;">
          <label for="lien">lien</label>
          <input type="url" name="lien" id="lien" value="<?= htmlspecialchars((string) ($oldInput['lien'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
        </div>
        <div class="form-group js-online-field" style="display:none;">
          <label for="duree_online">duree_online</label>
          <input type="number" min="1" name="duree_online" id="duree_online" value="<?= htmlspecialchars((string) ($oldInput['duree_online'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
        </div>
        <div class="form-group js-presentiel-field" style="display:none;">
          <label for="adresse">adresse</label>
          <input type="text" name="adresse" id="adresse" value="<?= htmlspecialchars((string) ($oldInput['adresse'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
        </div>
        <div class="form-group js-presentiel-field" style="display:none;">
          <label for="salle">salle</label>
          <input type="text" name="salle" id="salle" value="<?= htmlspecialchars((string) ($oldInput['salle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
        </div>
        <div class="form-group js-presentiel-field" style="display:none;">
          <label for="duree_presentiel">duree_presentiel</label>
          <input type="number" min="1" name="duree_presentiel" id="duree_presentiel" value="<?= htmlspecialchars((string) ($oldInput['duree_presentiel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
        </div>
      </div>
      <div class="actions-row">
        <button type="submit" class="btn-primary">Enregistrer</button>
        <button type="button" class="btn-secondary" id="critere-cancel-btn">Annuler</button>
      </div>
    </form>
  </div>

  <div class="back-card">
    <div class="table-tools">
      <div class="table-tools__group">
        <label for="criteres-search">Recherche</label>
        <input type="search" id="criteres-search" class="table-tools__input" placeholder="Rechercher formation, type ou specialite..." />
      </div>
      <div class="table-tools__group">
        <label for="criteres-sort">Tri</label>
        <select id="criteres-sort" class="table-tools__select">
          <option value="az">A-Z</option>
          <option value="za">Z-A</option>
        </select>
      </div>
      <button type="button" class="btn-secondary" id="criteres-word-btn">Exporter Word</button>
    </div>
    <div id="criteres-stats" class="stats-circles"></div>
    <div class="table-wrap">
      <table class="data-table" id="criteres-table">
        <thead>
          <tr>
            <th>id</th>
            <th>formation</th>
            <th>type</th>
            <th>lien</th>
            <th>duree_online</th>
            <th>adresse</th>
            <th>salle</th>
            <th>duree_presentiel</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($criteres as $critere): ?>
            <?php $specialite = (string) ($formationSpecialites[$critere['nom_formation'] ?? ''] ?? ''); ?>
            <tr
              data-name="<?= htmlspecialchars(strtolower((string) ($critere['nom_formation'] ?? '')), ENT_QUOTES, 'UTF-8') ?>"
              data-type="<?= htmlspecialchars(strtolower((string) ($critere['type'] ?? '')), ENT_QUOTES, 'UTF-8') ?>"
              data-specialite="<?= htmlspecialchars(strtolower($specialite), ENT_QUOTES, 'UTF-8') ?>"
              data-specialite-label="<?= htmlspecialchars($specialite, ENT_QUOTES, 'UTF-8') ?>"
            >
              <td><?= (int) $critere['id'] ?></td>
              <td><?= htmlspecialchars($critere['nom_formation'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($critere['type'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($critere['lien'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string) ($critere['duree_online'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($critere['adresse'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($critere['salle'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string) ($critere['duree_presentiel'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td>
                <button
                  type="button"
                  class="btn-sm btn-sm--edit critere-edit-btn"
                  data-id="<?= (int) $critere['id'] ?>"
                  data-nom-formation="<?= htmlspecialchars($critere['nom_formation'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-type="<?= htmlspecialchars($critere['type'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-lien="<?= htmlspecialchars($critere['lien'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-duree-online="<?= htmlspecialchars((string) ($critere['duree_online'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                  data-adresse="<?= htmlspecialchars($critere['adresse'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-salle="<?= htmlspecialchars($critere['salle'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-duree-presentiel="<?= htmlspecialchars((string) ($critere['duree_presentiel'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                >
                  Modifier
                </button>
                <form method="post" action="index.php?r=back/criteres/delete" style="display:inline-block;">
                  <input type="hidden" name="id" value="<?= (int) $critere['id'] ?>" />
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

