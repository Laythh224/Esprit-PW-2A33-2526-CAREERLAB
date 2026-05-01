<?php
session_start();
require_once 'models/Database.php';

$pdo = Database::getInstance()->getConnection();

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout_admin_about') {
    unset($_SESSION['admin_entreprise_nom']);
    header('Location: admin_about.php');
    exit;
}

// Handle login specifically for this page if needed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_admin_about'])) {
    $nom = htmlspecialchars(strip_tags(trim($_POST['nom_entreprise'] ?? '')));
    $email = htmlspecialchars(strip_tags(trim($_POST['email_entreprise'] ?? '')));
    if (!empty($nom) && !empty($email)) {
        $_SESSION['admin_entreprise_nom'] = $nom;
    }
}

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$baseUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/projet%20wweb/";

$isLoggedIn = isset($_SESSION['admin_entreprise_nom']);
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
<?php if (!$isLoggedIn): ?>
<div class="login-page-wrapper">
    <div class="login-card">
        <div class="login-header text-center mb-4">
            <div class="logo-box mb-3">
                <i class="fas fa-user-tie fa-3x text-primary"></i>
            </div>
            <h2 class="fw-bold">Espace Entreprise</h2>
            <p class="text-muted">Connectez-vous pour accéder aux candidatures</p>
        </div>
        <form action="admin_about.php" method="POST">
            <input type="hidden" name="login_admin_about" value="1">
            <div class="form-group mb-3">
                <label class="form-label"><i class="fas fa-building me-2"></i>Nom de l'entreprise</label>
                <input type="text" name="nom_entreprise" class="form-control rounded-pill px-4 py-2" placeholder="Ex: Career Lab" required>
            </div>
            <div class="form-group mb-3">
                <label class="form-label"><i class="fas fa-envelope me-2"></i>Email entreprise</label>
                <input type="email" name="email_entreprise" class="form-control rounded-pill px-4 py-2" placeholder="admin@entreprise.com" required>
            </div>
            <div class="form-group mb-4">
                <label class="form-label"><i class="fas fa-lock me-2"></i>Mot de passe</label>
                <input type="password" name="mdp_entreprise" class="form-control rounded-pill px-4 py-2" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">
                Se connecter
            </button>
        </form>
    </div>
</div>

<style>
    .login-page-wrapper {
        min-height: 100vh;
        background: linear-gradient(135deg, #87CEEB 0%, #E0F7FA 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        padding: 40px;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.5);
    }
    .logo-box {
        width: 80px;
        height: 80px;
        background: #f0f9ff;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    .form-control:focus {
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        border-color: #4f46e5;
    }
</style>

<?php else: ?>
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
          <div>
            <a href="admin_about.php?action=logout_admin_about" class="btn btn-danger btn-round fw-bold shadow-sm">
                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
            </a>
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
                    $stmtCount = $pdo->prepare("
                        SELECT COUNT(c.id) 
                        FROM Candidature c 
                        JOIN OpportuniteTravail t ON c.offre_id = t.id 
                        WHERE t.entreprise = :entreprise
                    ");
                    $stmtCount->execute(['entreprise' => $_SESSION['admin_entreprise_nom']]);
                    $count = $stmtCount->fetchColumn();
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
                statut VARCHAR(20) DEFAULT 'en_attente',
                nom_candidat VARCHAR(255) NULL,
                email_candidat VARCHAR(255) NULL,
                cv_texte TEXT NULL,
                score_test INT DEFAULT 0,
                score_ia INT DEFAULT 0,
                compatibilite INT DEFAULT 0,
                niveau VARCHAR(50) NULL,
                recommandation VARCHAR(50) NULL,
                feedback TEXT NULL
            )");

            $stmt = $pdo->prepare("
                SELECT c.*, t.titre, t.entreprise, t.localisation, t.domaine, t.type_contrat
                FROM Candidature c
                JOIN OpportuniteTravail t ON c.offre_id = t.id
                WHERE t.entreprise = :entreprise
                ORDER BY c.score_ia DESC, c.date_postulation DESC
            ");
            $stmt->execute(['entreprise' => $_SESSION['admin_entreprise_nom']]);
            $applications = $stmt->fetchAll();
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
                    <table class="table table-hover mb-0 align-middle">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th class="ps-4 py-3 border-0">CANDIDAT</th>
                                <th class="py-3 border-0">POSTE</th>
                                <th class="py-3 border-0 text-center">SCORE IA</th>
                                <th class="py-3 border-0 text-center">RECOMMANDATION</th>
                                <th class="py-3 border-0 text-end pe-4">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                            <tr style="transition: all 0.2s;" id="row-<?= $app['id'] ?>">
                                <td class="ps-4 py-4">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark" style="font-size: 1.1rem;">
                                            <?= htmlspecialchars($app['nom_candidat'] ?? 'Anonyme') ?>
                                            <?php if (($app['score_ia'] ?? 0) >= 80 || ($app['recommandation'] ?? '') === 'Accepter'): ?>
                                                <span class="badge bg-danger ms-1" title="Excellent profil">🔥 Bon candidat</span>
                                            <?php endif; ?>
                                        </span>
                                        <small class="text-muted"><i class="fas fa-envelope me-1"></i><?= htmlspecialchars($app['email_candidat'] ?? 'N/A') ?></small>
                                        <small class="text-muted mt-1"><i class="far fa-clock me-1"></i><?= date('d M, Y', strtotime($app['date_postulation'])) ?></small>
                                    </div>
                                </td>
                                <td class="py-4">
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
                                <td class="py-4 text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <h5 class="fw-bold text-primary mb-1"><?= $app['score_ia'] ?? 0 ?>%</h5>
                                        <div class="progress" style="height: 6px; width: 60px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $app['score_ia'] ?? 0 ?>%;"></div>
                                        </div>
                                        <small class="text-muted mt-1" style="font-size: 0.7rem;">Compat: <?= $app['compatibilite'] ?? 0 ?>%</small>
                                    </div>
                                </td>
                                <td class="py-4 text-center">
                                    <?php 
                                    $rec = $app['recommandation'] ?? 'Refuser';
                                    $badgeColor = $rec === 'Accepter' ? 'success' : ($rec === 'À revoir' ? 'warning text-dark' : 'danger');
                                    ?>
                                    <span class="badge bg-<?= $badgeColor ?> px-3 py-2 rounded-pill shadow-sm mb-1"><?= $rec ?></span>
                                    <div class="mt-1">
                                        <span class="badge bg-light text-dark border"><i class="fas fa-layer-group me-1"></i><?= $app['niveau'] ?? 'N/A' ?></span>
                                    </div>
                                </td>
                                <td class="py-4 text-end pe-4">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button class="btn btn-sm btn-info rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#feedbackModal<?= $app['id'] ?>">
                                            <i class="fas fa-robot me-1"></i> Détails IA
                                        </button>
                                        <a href="index.php?action=deleteCandidature&id=<?= $app['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Voulez-vous vraiment annuler cette candidature ?')">
                                            Refuser
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Feedback Modal -->
                            <div class="modal fade" id="feedbackModal<?= $app['id'] ?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                                  <div class="modal-header text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); border-radius: 15px 15px 0 0;">
                                    <h5 class="modal-title fw-bold"><i class="fas fa-brain me-2"></i> Rapport IA - <?= htmlspecialchars($app['nom_candidat'] ?? 'Candidat') ?></h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body p-4">
                                      <?= $app['feedback'] ?? '<p class="text-muted">Aucun rapport disponible.</p>' ?>
                                  </div>
                                  <div class="modal-footer border-0 p-3 bg-light" style="border-radius: 0 0 15px 15px;">
                                      <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Fermer</button>
                                  </div>
                                </div>
                              </div>
                            </div>
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
<?php endif; ?>

<!-- Core JS -->
<script src="assets/js/core/jquery-3.7.1.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/js/kaiadmin.min.js"></script>
</body>
</html>
