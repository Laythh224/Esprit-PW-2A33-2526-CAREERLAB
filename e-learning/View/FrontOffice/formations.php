<?php
require __DIR__ . '/../Layouts/front_header.php';
?>
<main class="main-wrap">
  <h1 class="page-title">Catalogue des formations</h1>
  <p class="page-subtitle">Liste des formations et des criteres associes.</p>

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
            <span class="card__meta">date_debut: <?= htmlspecialchars($formation['date_debut'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
            <span class="card__meta">date_fin: <?= htmlspecialchars($formation['date_fin'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
            <span class="card__meta">duree: <?= (int) ($formation['duree'] ?? 0) ?> jours</span>
          </p>
          <?php if (!empty($formation['criteres'])): ?>
            <ul class="list-resources" style="margin-top: 1rem">
              <?php foreach ($formation['criteres'] as $critere): ?>
                <li>
                  <div>
                    <strong>type: <?= htmlspecialchars($critere['type'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
                    <?php if (($critere['type'] ?? '') === 'online'): ?>
                      <div class="card__meta">lien: <?= htmlspecialchars($critere['lien'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                      <div class="card__meta">duree_online: <?= htmlspecialchars((string) ($critere['duree_online'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    <?php else: ?>
                      <div class="card__meta">adresse: <?= htmlspecialchars($critere['adresse'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                      <div class="card__meta">salle: <?= htmlspecialchars($critere['salle'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                      <div class="card__meta">duree_presentiel: <?= htmlspecialchars((string) ($critere['duree_presentiel'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="card__meta" style="margin-top: 1rem">Aucun critere pour cette formation.</p>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/../Layouts/front_footer.php'; ?>

