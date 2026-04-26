<?php
require_once 'models/Database.php';

$pdo = Database::getInstance()->getConnection();

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$baseUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/projet%20wweb/";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>À Propos | Admin Career Lab</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <link rel="icon" href="assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

  <!-- Fonts and icons -->
  <script src="assets/js/plugin/webfont/webfont.min.js"></script>
  <script>
    WebFont.load({
      google: { families: ["Public Sans:300,400,500,600,700"] },
      custom: {
        families: ["Font Awesome 5 Solid","Font Awesome 5 Regular","Font Awesome 5 Brands","simple-line-icons"],
        urls: ["assets/css/fonts.min.css"],
      },
      active: function () { sessionStorage.fonts = true; },
    });
  </script>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/plugins.min.css" />
  <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
  <link rel="stylesheet" href="assets/css/demo.css" />
</head>
<body>
<div class="wrapper">

  <!-- ===================== SIDEBAR ===================== -->
  <div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
      <div class="logo-header" data-background-color="dark">
        <a href="index.php?action=home">
          <span class="sub-item">Career Lab</span>
        </a>
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
          <li class="nav-section">
            <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
            <h4 class="text-section">Gestion</h4>
          </li>
          <li class="nav-item submenu">
            <a data-bs-toggle="collapse" href="#tables">
              <i class="fas fa-table"></i><p>les offres</p><span class="caret"></span>
            </a>
            <div class="collapse show" id="tables">
              <ul class="nav nav-collapse">
                <li><a href="admin.php"><span class="sub-item">les offres</span></a></li>
                <li class="active"><a href="admin_about.php"><span class="sub-item">à propos</span></a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a href="#">
              <i class="fas fa-graduation-cap"></i><p>E-learning</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#">
              <i class="fas fa-user-tie"></i><p>Métiers</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#">
              <i class="fas fa-newspaper"></i><p>Blog</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#">
              <i class="fas fa-users"></i><p>Utilisateurs</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#">
              <i class="fas fa-clipboard-check"></i><p>Evaluation</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!-- ===================== END SIDEBAR ===================== -->

  <div class="main-panel">
    <div class="content">
      <div class="page-inner">
        
        <!-- Header Section -->
        <div class="page-header d-flex align-items-center justify-content-between mb-4">
          <div>
            <h4 class="page-title mb-1">À Propos</h4>
            <ul class="breadcrumbs">
              <li class="nav-home"><a href="admin.php"><i class="icon-home"></i></a></li>
              <li class="separator"><i class="icon-arrow-right"></i></li>
              <li class="nav-item"><a href="#">Admin</a></li>
              <li class="separator"><i class="icon-arrow-right"></i></li>
              <li class="nav-item"><a href="#">Candidatures</a></li>
            </ul>
          </div>
        </div>

        <div class="mb-4">
            <h2 class="fw-bold text-primary" style="font-size: 2rem; letter-spacing: -0.5px;">Intéressé par cette offre</h2>
            <p class="text-muted">Gérez et suivez l'ensemble de vos candidatures envoyées.</p>
        </div>
        
        <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
          <div class="card-header bg-white border-0 py-4 px-4">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold"><i class="fas fa-list-ul me-2 text-primary"></i> Candidatures reçues</h5>
                <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
                    <div class="alert alert-success py-1 px-3 mb-0 small" style="border-radius: 10px;">Candidature annulée avec succès.</div>
                <?php endif; ?>
                <span class="badge bg-primary-gradient px-3 py-2 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                    <?php
                    // Count entries
                    $count = $pdo->query("SELECT COUNT(*) FROM Candidature")->fetchColumn();
                    echo $count . " Candidature" . ($count > 1 ? "s" : "");
                    ?>
                </span>
            </div>
          </div>
          <div class="card-body p-0">
            <?php
            // Create table if it doesn't exist (to avoid errors on first load)
            $pdo->exec("CREATE TABLE IF NOT EXISTS Candidature (
                id INT AUTO_INCREMENT PRIMARY KEY,
                offre_id INT NOT NULL,
                date_postulation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                statut VARCHAR(20) DEFAULT 'en_attente'
            )");

            $applications = $pdo->query("
                SELECT c.id as candidature_id, c.date_postulation, c.statut, t.titre, t.entreprise, t.localisation, t.domaine, t.type_contrat
                FROM Candidature c
                JOIN OpportuniteTravail t ON c.offre_id = t.id
                ORDER BY c.date_postulation DESC
            ")->fetchAll();
            ?>

            <?php if (empty($applications)): ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-inbox fa-4x text-light"></i>
                    </div>
                    <h5 class="text-muted fw-bold">Aucune candidature pour le moment</h5>
                    <p class="text-muted px-5">Les opportunités pour lesquelles vous postulez apparaîtront ici automatiquement.</p>
                    <a href="index.php?action=offres" class="btn btn-primary rounded-pill px-4 mt-3">Voir les offres</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th class="ps-4 py-3 border-0">DATE</th>
                                <th class="py-3 border-0">POSTE</th>
                                <th class="py-3 border-0">ENTREPRISE</th>
                                <th class="py-3 border-0">LOCALISATION</th>
                                <th class="py-3 border-0 text-end pe-4">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                            <tr style="transition: all 0.2s;" id="row-<?= $app['candidature_id'] ?>">
                                <td class="ps-4 py-4 align-middle">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark"><?= date('d M, Y', strtotime($app['date_postulation'])) ?></span>
                                        <small class="text-muted"><?= date('H:i', strtotime($app['date_postulation'])) ?></small>
                                    </div>
                                </td>
                                <td class="py-4 align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-briefcase text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($app['titre']) ?></div>
                                            <span class="badge bg-soft-primary text-primary mt-1" style="font-size: 0.65rem; background: #e0e7ff; color: #4338ca; border: none;"><?= htmlspecialchars($app['domaine'] ?? 'Général') ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 align-middle">
                                    <span class="text-muted"><i class="far fa-building me-1"></i><?= htmlspecialchars($app['entreprise']) ?></span>
                                </td>
                                <td class="py-4 align-middle">
                                    <span class="badge rounded-pill bg-light text-dark fw-normal border" style="padding: 0.5rem 1rem;">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i> <?= htmlspecialchars($app['localisation']) ?>
                                    </span>
                                </td>
                                <td class="py-4 align-middle text-end pe-4">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button class="btn btn-sm btn-success rounded-pill px-3" onclick="this.innerHTML='<i class=\'fas fa-check me-1\'></i> Accepté'; this.disabled=true;">
                                            Accepter
                                        </button>
                                        <a href="index.php?action=deleteCandidature&id=<?= $app['candidature_id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Voulez-vous vraiment annuler cette candidature ?')">
                                            Annuler
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
          </div>
          <div class="card-footer bg-light border-0 text-center py-3">
              <small class="text-muted">Carréer Lab Admin Interface - &copy; <?= date('Y') ?></small>
          </div>
        </div>
      </div>
    </div>
  </div><!-- main-panel -->

</div><!-- wrapper -->

<!-- Core JS -->
<script src="assets/js/core/jquery-3.7.1.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/js/kaiadmin.min.js"></script>
</body>
</html>
