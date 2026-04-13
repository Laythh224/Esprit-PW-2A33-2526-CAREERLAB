<?php
$title = 'Planning_Option — Back Office MVC';
$active = 'planning_option';
require __DIR__ . '/../layouts/back_header.php';
?>
<header class="back-topbar">
  <h1>CRUD Planning_Option (PDO)</h1>
  <span style="font-size: 0.875rem; color: #64748b">Liaison entre planning et optionn</span>
</header>
<div class="back-content">
  <?php if (!empty($flash)): ?>
    <div class="back-card" style="border-left: 4px solid #3381ff;">
      <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>

  <div class="back-card">
    <h2 class="back-card__title">Ajouter une liaison</h2>
    <form method="post" action="index.php?r=back/planning-option/store">
      <div class="form-grid">
        <div class="form-group">
          <label for="id_planning">id_planning</label>
          <select id="id_planning" name="id_planning" required>
            <option value="">Choisir un planning</option>
            <?php foreach ($planningChoices as $planning): ?>
              <option value="<?= (int) $planning['id_planning'] ?>">
                <?= (int) $planning['id_planning'] ?> - <?= htmlspecialchars($planning['nom_de_formation'] ?? '', ENT_QUOTES, 'UTF-8') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="id_option">id_option</label>
          <select id="id_option" name="id_option" required>
            <option value="">Choisir une option</option>
            <?php foreach ($optionChoices as $option): ?>
              <option value="<?= (int) $option['id_option'] ?>">
                <?= (int) $option['id_option'] ?> - <?= htmlspecialchars($option['nom_option'] ?? '', ENT_QUOTES, 'UTF-8') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="actions-row">
        <button type="submit" class="btn-primary">Enregistrer</button>
      </div>
    </form>
  </div>

  <div class="back-card">
    <h2 class="back-card__title">Liste des liaisons</h2>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>id_planning</th>
            <th>nom_de_formation</th>
            <th>id_option</th>
            <th>nom_option</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($links as $link): ?>
            <tr>
              <td><?= (int) $link['id_planning'] ?></td>
              <td><?= htmlspecialchars($link['nom_de_formation'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= (int) $link['id_option'] ?></td>
              <td><?= htmlspecialchars($link['nom_option'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td>
                <form method="post" action="index.php?r=back/planning-option/delete" style="display:inline-block;">
                  <input type="hidden" name="id_planning" value="<?= (int) $link['id_planning'] ?>" />
                  <input type="hidden" name="id_option" value="<?= (int) $link['id_option'] ?>" />
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
<?php require __DIR__ . '/../layouts/back_footer.php'; ?>
