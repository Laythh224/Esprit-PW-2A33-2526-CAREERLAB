<?php
$title = 'Optionn — Front Office MVC';
require __DIR__ . '/../layouts/front_header.php';
?>
<main class="main-wrap">
  <h1 class="page-title">Catalogue optionn</h1>
  <p class="page-subtitle">Donnees issues de la table optionn : nom_option, specialite, description.</p>
  <div class="card-grid">
    <?php foreach ($options as $option): ?>
      <article class="card">
        <span class="badge">id_option: <?= (int) $option['id_option'] ?></span>
        <h2>
          <span class="card__label">nom_option:</span>
          <span class="card__value"><?= htmlspecialchars($option['nom_option'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
        </h2>
        <p>
          <span class="card__meta">specialite: <?= htmlspecialchars($option['specialite'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
          <span class="card__meta">description: <?= htmlspecialchars($option['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
        </p>
        <div class="card__actions">
          <a class="btn btn--primary" href="/careerlabb/e-learning/front/formation-detail.html?id=<?= (int) $option['id_option'] ?>">Voir le détail</a>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</main>
<?php require __DIR__ . '/../layouts/front_footer.php'; ?>

