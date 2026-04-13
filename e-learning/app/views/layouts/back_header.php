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
    <link rel="stylesheet" href="assets/css/back-layout.css" />
  </head>
  <body>
    <div class="back-shell">
      <aside class="back-sidebar" aria-label="Navigation administration">
        <div class="back-sidebar__brand">
          <strong>CareerLab</strong>
          <small>Back office · e_learning (MVC)</small>
        </div>
        <ul class="back-nav">
          <li><a href="index.php?r=back/options" class="<?= $active === 'options' ? 'is-active' : '' ?>">Optionn</a></li>
          <li><a href="index.php?r=back/planning" class="<?= $active === 'planning' ? 'is-active' : '' ?>">Planning</a></li>
          <li><a href="index.php?r=back/planning-option" class="<?= $active === 'planning_option' ? 'is-active' : '' ?>">Planning_Option</a></li>
          <li><a href="index.php?r=front/options">Front office</a></li>
        </ul>
      </aside>
      <div class="back-main">
