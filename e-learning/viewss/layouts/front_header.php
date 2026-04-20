<?php
$title = $title ?? 'Front Office';
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/careerlabb/e-learning/viewss/css/front-layout.css" />
  </head>
  <body>
    <header class="site-header">
      <div class="site-header__inner">
        <a href="/careerlabb/e-learning/front/index.html" class="logo">
          <span class="logo__mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" role="img" aria-label="">
              <path d="M12 11.8a4.4 4.4 0 1 0 0-8.8 4.4 4.4 0 0 0 0 8.8Zm-4.8 8.2.9-4h1.8l2.1 3 2.1-3h1.8l.9 4H19v1.5H5V20h2.2Z" fill="currentColor" />
            </svg>
          </span>
          <span class="logo__text">CareerLab e_learning</span>
        </a>
        <nav class="nav-main" aria-label="Navigation principale">
            <a href="/careerlabb/e-learning/front/formations.html" class="is-active">Optionn</a>
            <a href="/careerlabb/e-learning/front/certifications.html">Planning</a>
            <a href="/careerlabb/indexF.html" class="nav-main__button">Main page</a>
        </nav>
      </div>
    </header>



