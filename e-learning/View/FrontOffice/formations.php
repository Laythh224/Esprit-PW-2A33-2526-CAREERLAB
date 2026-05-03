<?php
require __DIR__ . '/../Layouts/front_header.php';
?>
<main class="main-wrap">
  <h1 class="page-title">Catalogue des formations</h1>
  <p class="page-subtitle">Liste des formations et des sessions associees.</p>

  <?php if ($formations === []): ?>
    <div class="card">
      <p>Aucune formation disponible.</p>
    </div>
  <?php else: ?>
    <div class="card-grid">
      <?php foreach ($formations as $formation): ?>
        <article class="card">
          <span class="badge"><?= htmlspecialchars($formation['niveau'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
          <h2><?= htmlspecialchars($formation['nom_formation'] ?? '', ENT_QUOTES, 'UTF-8') ?></h2>
          <p>
            <span class="card__meta">specialite: <?= htmlspecialchars($formation['specialite'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
            <span class="card__meta">description: <?= htmlspecialchars($formation['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
            <span class="card__meta">nb_place: <?= (int) ($formation['nb_place'] ?? 0) ?> places</span>
          </p>
          <?php if (!empty($formation['sessions'])): ?>
            <ul class="list-resources" style="margin-top: 1rem">
              <?php foreach ($formation['sessions'] as $session): ?>
                <li>
                  <div>
                    <strong>type: <?= htmlspecialchars($session['type'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
                    <div class="card__meta">date_debut: <?= htmlspecialchars((string) ($session['date_debut'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="card__meta">date_fin: <?= htmlspecialchars((string) ($session['date_fin'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    <?php if (($session['type'] ?? '') === 'online'): ?>
                      <div class="card__meta">lien: <?= htmlspecialchars($session['lien'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                      <div class="card__meta">duree_online: <?= htmlspecialchars((string) ($session['duree_online'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    <?php else: ?>
                      <div class="card__meta">adresse: <?= htmlspecialchars($session['adresse'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                      <div class="card__meta">salle: <?= htmlspecialchars($session['salle'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                      <div class="card__meta">duree_presentiel: <?= htmlspecialchars((string) ($session['duree_presentiel'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="card__meta" style="margin-top: 1rem">Aucune session pour cette formation.</p>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/../Layouts/front_footer.php'; ?>
