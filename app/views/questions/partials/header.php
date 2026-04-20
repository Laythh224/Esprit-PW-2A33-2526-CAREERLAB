<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?= htmlspecialchars((string) ($pageTitle ?? 'Evaluation - Questions'), ENT_QUOTES, 'UTF-8'); ?></title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
  </head>
  <body>
    <div class="wrapper">
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <div class="logo-header" data-background-color="dark">
            <a href="index.php?route=dashboard"><span class="sub-item">Career Lab</span></a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
              <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
          </div>
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item">
                <a href="index.php?route=dashboard"><i class="fas fa-home"></i><p>Dashboard</p></a>
              </li>
              <li class="nav-item active submenu">
                <a data-bs-toggle="collapse" href="#forms" aria-expanded="true">
                  <i class="fas fa-pen-square"></i>
                  <p>evaluation</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse show" id="forms">
                  <ul class="nav nav-collapse">
                    <li class="<?= ($activeAction ?? 'list') === 'list' ? 'active' : ''; ?>">
                      <a href="index.php?route=evaluation"><span class="sub-item">Liste des questions</span></a>
                    </li>
                    <li class="<?= ($activeAction ?? '') === 'add' ? 'active' : ''; ?>">
                      <a href="index.php?route=evaluation&action=add"><span class="sub-item">Ajouter une question</span></a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item"><a href="index.php?route=offres"><i class="fas fa-table"></i><p>les offres</p></a></li>
              <li class="nav-item"><a href="index.php?route=elearning"><i class="fas fa-map-marker-alt"></i><p>E_learning</p></a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <div class="logo-header" data-background-color="dark">
              <a href="index.php?route=dashboard" class="logo">
                <img src="assets/img/kaiadmin/logo_light.svg" alt="logo" class="navbar-brand" />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
              </div>
              <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
            </div>
          </div>
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid">
              <div class="ms-md-auto py-2">
                <a href="index.php?route=front" class="btn btn-primary btn-sm">Aller au front-office</a>
              </div>
            </div>
          </nav>
        </div>

        <div class="container">
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3"><?= htmlspecialchars((string) ($pageSubtitle ?? 'Questions'), ENT_QUOTES, 'UTF-8'); ?></h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php?route=dashboard"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="index.php?route=evaluation">Evaluation</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Questions</a></li>
              </ul>
            </div>
