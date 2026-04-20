<?php
$title = 'Options';
$active = 'options';
require __DIR__ . '/../layouts/back_header.php';
?>
<header class="back-topbar">
  <h1>Options</h1>
</header>
<div class="back-content">
  <?php if (!empty($flash)): ?>
    <div id="option-flash-message" data-message="<?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>" hidden></div>
  <?php endif; ?>

  <div class="back-card">
    <h2 class="back-card__title" id="option-form-title">Ajouter une option</h2>
    <form method="post" action="index.php?r=back/options/store" id="option-form">
      <input type="hidden" name="id_option" id="id_option" value="" />
      <div class="form-grid">
        <div class="form-group" style="grid-column: 1 / -1">
          <label for="nom_option">nom_option</label>
          <input type="text" name="nom_option" id="nom_option" placeholder="Ex. Développement web" />
        </div>
        <div class="form-group">
          <label for="specialite">specialite</label>
          <input type="text" name="specialite" id="specialite" placeholder="Ex. Front-end" />
        </div>
        <div class="form-group" style="grid-column: 1 / -1">
          <label for="description">description</label>
          <textarea name="description" id="description" placeholder="Description de l'option"></textarea>
        </div>
      </div>
      <div class="actions-row">
        <button type="submit" class="btn-primary" id="save-btn">Enregistrer</button>
        <button type="button" class="btn-secondary" id="cancel-edit-btn">Annuler</button>
      </div>
    </form>
  </div>

  <div class="back-card">
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>id_option</th>
            <th>nom_option</th>
            <th>specialite</th>
            <th>description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($options as $option): ?>
            <tr>
              <td><?= (int) $option['id_option'] ?></td>
              <td><?= htmlspecialchars($option['nom_option'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($option['specialite'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($option['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td>
                <button
                  type="button"
                  class="btn-sm btn-sm--edit option-edit-btn"
                  data-id="<?= (int) $option['id_option'] ?>"
                  data-nom="<?= htmlspecialchars($option['nom_option'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-specialite="<?= htmlspecialchars($option['specialite'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-description="<?= htmlspecialchars($option['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
                  Modifier
                </button>
                <form method="post" action="index.php?r=back/options/delete" style="display:inline-block;">
                  <input type="hidden" name="id_option" value="<?= (int) $option['id_option'] ?>" />
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
<script src="/careerlabb/e-learning/viewss/js/back-form-validation.js"></script>
<?php require __DIR__ . '/../layouts/back_footer.php'; ?>

