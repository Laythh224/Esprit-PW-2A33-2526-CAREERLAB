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
    <link rel="stylesheet" href="/careerlabb/e-learning/View/assets/css/back-layout.css" />
  </head>
  <body>
    <div class="back-shell">
      <aside class="back-sidebar" aria-label="Navigation administration">
        <div class="back-sidebar__brand">
          <img src="/careerlabb/e-learning/View/assets/img/careerlab-logo.png" alt="CareerLab" class="back-sidebar__logo" />
          <small>Back office · MVC</small>
        </div>
        <ul class="back-nav">
          <li><a href="index.php?r=back/formations" class="<?= $active === 'formations' ? 'is-active' : '' ?>">Formations</a></li>
          <li><a href="index.php?r=back/sessions" class="<?= $active === 'sessions' ? 'is-active' : '' ?>">Sessions</a></li>
          <li><a href="/careerlabb/indexF.html">Front Office</a></li>
          <li><a href="/careerlabb/index.html">Main page</a></li>
        </ul>
      </aside>
      <div class="back-main">

