<?php
$title = 'Optionn — Front Office MVC';
require __DIR__ . '/../layouts/front_header.php';
?>
<main class="main-wrap">
  <h1 class="page-title">Catalogue optionn</h1>
  <p class="page-subtitle">Affichage depuis MySQL via MVC et PDO.</p>
  <div class="card-grid">
    <?php foreach ($options as $option): ?>
      <article class="card">
        <span class="badge">id_option: <?= (int) $option['id_option'] ?></span>
        <h2 style="margin: 0.75rem 0 0.5rem; font-size: 1.15rem">
          nom_option: <?= htmlspecialchars($option['nom_option'] ?? '', ENT_QUOTES, 'UTF-8') ?>
        </h2>
        <p style="margin: 0; color: #64748b; font-size: 0.9rem">
          specialite: <?= htmlspecialchars($option['specialite'] ?? '', ENT_QUOTES, 'UTF-8') ?> ·
          description: <?= htmlspecialchars($option['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>
        </p>
      </article>
    <?php endforeach; ?>
  </div>
</main>
<?php require __DIR__ . '/../layouts/front_footer.php'; ?>
