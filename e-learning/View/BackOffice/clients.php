<?php
$title = 'Clients';
$active = 'clients';
require __DIR__ . '/../Layouts/back_header.php';
?>
<header class="back-topbar">
  <h1>Clients</h1>
  <p>Consultez les inscriptions et effectuez les actions de suivi.</p>
</header>
<div class="back-content">
  <?php if (!empty($flash)): ?>
    <div id="client-flash-message" data-message="<?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>" hidden></div>
  <?php endif; ?>

  <p class="back-card back-card--note">Les inscriptions s'effectuent depuis le <a href="/careerlabb/e-learning/index.php?r=front/formations">front office</a> (bouton S'inscrire sur une formation). Cette page permet la consultation, la recherche et la suppression.</p>

  <div class="back-card">
    <div class="table-tools">
      <div class="table-tools__group">
        <label for="clients-search">Recherche</label>
        <input type="search" id="clients-search" class="table-tools__input" placeholder="Rechercher nom, prenom, e-mail, CIN, niveau ou formation..." />
      </div>
      <div class="table-tools__group">
        <label for="clients-sort">Tri</label>
        <select id="clients-sort" class="table-tools__select">
          <option value="az">A-Z</option>
          <option value="za">Z-A</option>
        </select>
      </div>
      <p class="table-tools__result" id="clients-result-count"></p>
      <button type="button" class="btn-secondary" id="clients-word-btn">Exporter Word</button>
    </div>
    <div id="clients-stats" class="stats-circles stats-circles--client-niveau"></div>
    <div class="table-wrap">
      <table class="data-table" id="clients-table">
        <thead>
          <tr>
            <th>CIN</th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>E-mail</th>
            <th>Niveau</th>
            <th>Formation</th>
            <th>Age</th>
            <th>Telephone</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($clients as $client): ?>
            <?php
              $nom = (string) ($client['nom'] ?? '');
              $prenom = (string) ($client['prenom'] ?? '');
              $fullNameKey = strtolower(trim($nom . ' ' . $prenom));
              $niveau = (string) ($client['niveau'] ?? '');
              $nomFormationRow = (string) ($client['nom_formation'] ?? '');
            ?>
            <tr
              data-name="<?= htmlspecialchars($fullNameKey, ENT_QUOTES, 'UTF-8') ?>"
              data-cin="<?= htmlspecialchars(strtolower((string) ($client['cin'] ?? '')), ENT_QUOTES, 'UTF-8') ?>"
              data-email="<?= htmlspecialchars(strtolower((string) ($client['adresse'] ?? '')), ENT_QUOTES, 'UTF-8') ?>"
              data-niveau="<?= htmlspecialchars(strtolower($niveau), ENT_QUOTES, 'UTF-8') ?>"
              data-niveau-label="<?= htmlspecialchars($niveau, ENT_QUOTES, 'UTF-8') ?>"
              data-formation="<?= htmlspecialchars(strtolower($nomFormationRow), ENT_QUOTES, 'UTF-8') ?>"
            >
              <td><?= htmlspecialchars((string) ($client['cin'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string) ($client['adresse'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($niveau, ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($nomFormationRow !== '' ? $nomFormationRow : '—', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= (int) ($client['age'] ?? 0) ?></td>
              <td><?= htmlspecialchars((string) ($client['tel'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td>
                <form method="post" action="index.php?r=back/clients/delete" class="inline-form">
                  <input type="hidden" name="cin" value="<?= htmlspecialchars((string) ($client['cin'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
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
