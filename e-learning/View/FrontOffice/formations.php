<?php
$flash = $flash ?? null;
require __DIR__ . '/../Layouts/front_header.php';
?>
<main class="main-wrap">
  <header class="page-head">
    <h1 class="page-title">Catalogue des formations</h1>
    <p class="page-subtitle">Explorez nos parcours et choisissez une session disponible en ligne ou en presentiel.</p>
  </header>

  <?php if (!empty($flash)): ?>
    <div id="front-flash-message" class="flash-banner flash-banner--success" data-message="<?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>" hidden></div>
  <?php endif; ?>

  <?php if ($formations === []): ?>
    <div class="empty-state">
      <p>Aucune formation disponible.</p>
    </div>
  <?php else: ?>
    <div class="card-grid">
      <?php foreach ($formations as $formation): ?>
        <article class="card">
          <span class="badge"><?= htmlspecialchars($formation['niveau'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
          <h2><?= htmlspecialchars($formation['nom_formation'] ?? '', ENT_QUOTES, 'UTF-8') ?></h2>
          <p>
            <span class="card__meta"><span class="card__label-inline">Specialite:</span> <?= htmlspecialchars($formation['specialite'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
            <span class="card__meta"><span class="card__label-inline">Description:</span> <?= htmlspecialchars($formation['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
          </p>
          <?php if (!empty($formation['sessions'])): ?>
            <ul class="list-resources">
              <?php foreach ($formation['sessions'] as $session): ?>
                <li>
                  <div>
                    <strong>Type: <?= htmlspecialchars(($session['type'] ?? '') === 'online' ? 'En ligne' : 'Presentiel', ENT_QUOTES, 'UTF-8') ?></strong>
                    <div class="card__meta">Places disponibles: <?= (int) ($session['nb_place'] ?? 0) ?></div>
                    <div class="card__meta">Date debut: <?= htmlspecialchars((string) ($session['date_debut'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="card__meta">Date fin: <?= htmlspecialchars((string) ($session['date_fin'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    <?php if (($session['type'] ?? '') === 'online'): ?>
                      <div class="card__meta">Lien: <?= htmlspecialchars($session['lien'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                      <div class="card__meta">Duree en ligne: <?= htmlspecialchars((string) ($session['duree_online'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    <?php else: ?>
                      <div class="card__meta">Adresse: <?= htmlspecialchars($session['adresse'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                      <div class="card__meta">Salle: <?= htmlspecialchars($session['salle'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                      <div class="card__meta">Duree en presentiel: <?= htmlspecialchars((string) ($session['duree_presentiel'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                    <div class="card__actions">
                      <?php if ((int) ($session['nb_place'] ?? 0) > 0): ?>
                        <a
                          class="btn btn--primary"
                          href="index.php?r=front/inscription&amp;nom_formation=<?= rawurlencode((string) ($formation['nom_formation'] ?? '')) ?>&amp;session_id=<?= (int) ($session['id'] ?? 0) ?>"
                        >S'inscrire</a>
                      <?php else: ?>
                        <span class="badge badge--muted">Complet</span>
                      <?php endif; ?>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="card__meta card__meta--spaced">Aucune session active pour cette formation.</p>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>
<script src="/careerlabb/e-learning/View/assets/js/front-flash-alert.js"></script>
<?php require __DIR__ . '/../Layouts/front_footer.php'; ?>
