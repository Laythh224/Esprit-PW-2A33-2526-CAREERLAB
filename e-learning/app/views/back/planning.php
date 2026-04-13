<?php
$title = 'Planning — Back Office MVC';
$active = 'planning';
require __DIR__ . '/../layouts/back_header.php';
?>
<header class="back-topbar">
  <h1>CRUD Planning (PDO)</h1>
  <span style="font-size: 0.875rem; color: #64748b">date format: YYYY-MM-DD</span>
</header>
<div class="back-content">
  <?php if (!empty($flash)): ?>
    <div class="back-card" style="border-left: 4px solid #3381ff;">
      <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>

  <div class="back-card">
    <h2 class="back-card__title" id="planning-form-title">Ajouter un planning</h2>
    <form method="post" action="index.php?r=back/planning/store" id="planning-form">
      <input type="hidden" name="id_planning" id="id_planning" value="" />
      <div class="form-grid">
        <div class="form-group">
          <label for="nom_de_formation">nom_de_formation</label>
          <input type="text" name="nom_de_formation" id="nom_de_formation" placeholder="Ex. Session printemps web" />
        </div>
        <div class="form-group">
          <label for="date_debut">date_debut</label>
          <input type="text" name="date_debut" id="date_debut" placeholder="YYYY-MM-DD" />
        </div>
        <div class="form-group">
          <label for="date_fin">date_fin</label>
          <input type="text" name="date_fin" id="date_fin" placeholder="YYYY-MM-DD" />
        </div>
        <div class="form-group">
          <label for="TYPE">TYPE</label>
          <input type="text" name="TYPE" id="TYPE" placeholder="Ex. En ligne" />
        </div>
      </div>
      <div class="actions-row">
        <button type="submit" class="btn-primary">Enregistrer</button>
        <button type="button" class="btn-secondary" id="planning-cancel-btn">Annuler</button>
      </div>
    </form>
  </div>

  <div class="back-card">
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>id_planning</th>
            <th>nom_de_formation</th>
            <th>date_debut</th>
            <th>date_fin</th>
            <th>TYPE</th>
            <th>Durée (jours)</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($planningRows as $row): ?>
            <tr>
              <td><?= (int) $row['id_planning'] ?></td>
              <td><?= htmlspecialchars($row['nom_de_formation'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($row['date_debut'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($row['date_fin'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($row['TYPE'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= (int) $row['duree_jours'] ?></td>
              <td><?= htmlspecialchars($row['statut'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td>
                <button
                  type="button"
                  class="btn-sm btn-sm--edit planning-edit-btn"
                  data-id="<?= (int) $row['id_planning'] ?>"
                  data-nom="<?= htmlspecialchars($row['nom_de_formation'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-debut="<?= htmlspecialchars($row['date_debut'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-fin="<?= htmlspecialchars($row['date_fin'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  data-type="<?= htmlspecialchars($row['TYPE'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
                  Modifier
                </button>
                <form method="post" action="index.php?r=back/planning/delete" style="display:inline-block;">
                  <input type="hidden" name="id_planning" value="<?= (int) $row['id_planning'] ?>" />
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
<script>
  (function () {
    const form = document.getElementById('planning-form');
    const idInput = document.getElementById('id_planning');
    const nomInput = document.getElementById('nom_de_formation');
    const debutInput = document.getElementById('date_debut');
    const finInput = document.getElementById('date_fin');
    const typeInput = document.getElementById('TYPE');
    const title = document.getElementById('planning-form-title');
    const cancelBtn = document.getElementById('planning-cancel-btn');

    document.querySelectorAll('.planning-edit-btn').forEach((button) => {
      button.addEventListener('click', function () {
        idInput.value = this.dataset.id || '';
        nomInput.value = this.dataset.nom || '';
        debutInput.value = this.dataset.debut || '';
        finInput.value = this.dataset.fin || '';
        typeInput.value = this.dataset.type || '';
        form.action = 'index.php?r=back/planning/update';
        title.textContent = 'Modifier un planning';
      });
    });

    cancelBtn.addEventListener('click', function () {
      idInput.value = '';
      nomInput.value = '';
      debutInput.value = '';
      finInput.value = '';
      typeInput.value = '';
      form.action = 'index.php?r=back/planning/store';
      title.textContent = 'Ajouter un planning';
    });
  })();
</script>
<?php require __DIR__ . '/../layouts/back_footer.php'; ?>
