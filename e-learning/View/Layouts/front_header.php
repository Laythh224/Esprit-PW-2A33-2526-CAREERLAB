<?php
$title = $title ?? 'Front Office';
$active = $active ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/careerlabb/e-learning/View/assets/css/front-layout.css" />
  </head>
  <body>
    <header class="site-header">
      <div class="site-header__inner">
        <a href="index.php?r=front/formations" class="logo">
          <img src="/careerlabb/e-learning/View/assets/img/careerlab-logo.png" alt="CareerLab" class="logo__image" />
          <span class="logo__text">CareerLab Learning</span>
        </a>
        <nav class="nav-main" aria-label="Navigation principale">
          <a href="index.php?r=front/formations" class="<?= $active === 'formations' ? 'is-active' : '' ?>">Catalogue</a>
          <a href="/careerlabb/indexF.html" class="nav-main__button">Retour au site</a>
        </nav>
      </div>
    </header>

