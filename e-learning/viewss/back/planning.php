<?php
$title = 'Planning';
$active = 'planning';
require __DIR__ . '/../layouts/back_header.php';
?>
<header class="back-topbar">
  <h1>Planning</h1>
</header>
<div class="back-content">
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
          <input type="date" name="date_debut" id="date_debut" />
        </div>
        <div class="form-group">
          <label for="date_fin">date_fin</label>
          <input type="date" name="date_fin" id="date_fin" />
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
<script src="/careerlabb/e-learning/viewss/js/back-form-validation.js"></script>
<?php require __DIR__ . '/../layouts/back_footer.php'; ?>

