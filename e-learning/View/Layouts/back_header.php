<?php
$title = $title ?? 'Back Office';
$active = $active ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(E_LEARNING_WEB_BASE, ENT_QUOTES, 'UTF-8') ?>/View/assets/css/back-layout.css" />
  </head>
  <body>
    <?php if (defined('E_LEARNING_WEB_BASE')): ?>
    <script>
      window.E_LEARNING_WEB_BASE = <?= json_encode(E_LEARNING_WEB_BASE, JSON_UNESCAPED_SLASHES) ?>;
    </script>
    <?php endif; ?>
    <div class="back-shell">
      <aside class="back-sidebar" aria-label="Navigation administration">
        <div class="back-sidebar__brand">
          <img src="<?= htmlspecialchars(E_LEARNING_WEB_BASE, ENT_QUOTES, 'UTF-8') ?>/View/assets/img/careerlab-logo.png" alt="CareerLab" class="back-sidebar__logo" />
          <small>Pilotage de la plateforme</small>
        </div>
        <ul class="back-nav">
          <li><a href="index.php?r=back/formations" class="<?= $active === 'formations' ? 'is-active' : '' ?>">Formations</a></li>
          <li><a href="index.php?r=back/sessions" class="<?= $active === 'sessions' ? 'is-active' : '' ?>">Sessions</a></li>
          <li><a href="index.php?r=back/clients" class="<?= $active === 'clients' ? 'is-active' : '' ?>">Clients inscrits</a></li>
          <li><a href="../index.php?page=accueil">Voir le front office</a></li>
          <li><a href="../index.php?r=admin&amp;view=dashboard">Tableau de bord principal</a></li>
        </ul>
      </aside>
      <div class="back-main">

