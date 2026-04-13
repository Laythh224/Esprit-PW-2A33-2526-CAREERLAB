<?php
$title = $title ?? 'Front Office';
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="assets/css/front-layout.css" />
  </head>
  <body>
    <header class="site-header">
      <div class="site-header__inner">
        <a href="index.php?r=front/options" class="logo">CareerLab <span>e_learning</span></a>
        <nav class="nav-main" aria-label="Navigation principale">
          <a href="index.php?r=front/options" class="is-active">Optionn</a>
          <a href="index.php?r=back/options">Back office</a>
        </nav>
      </div>
    </header>
