<?php
require_once '../config/Database.php';

$pdo = Database::getInstance()->getConnection();

// Statistiques Offres
$totalTravail  = $pdo->query("SELECT COUNT(*) FROM OpportuniteTravail")->fetchColumn();
$totalStage    = $pdo->query("SELECT COUNT(*) FROM Stage")->fetchColumn();
$totalOffres   = $totalTravail + $totalStage;
$stagesOuverts = $pdo->query("SELECT COUNT(*) FROM Stage WHERE statut='disponible'")->fetchColumn();

// Statistiques Candidatures
$totalCandidatures = $pdo->query("SELECT COUNT(*) FROM Candidature")->fetchColumn();
$recentCandidatures = $pdo->query("SELECT c.*, 
    CASE WHEN c.type_offre = 'travail' THEN t.titre ELSE s.nom_societe END as offre_titre
    FROM Candidature c
    LEFT JOIN OpportuniteTravail t ON c.offre_id = t.id AND c.type_offre = 'travail'
    LEFT JOIN Stage s ON c.offre_id = s.id AND c.type_offre = 'stage'
    ORDER BY c.date_candidature DESC LIMIT 5")->fetchAll();

// Répartition par domaine (pour graphique - Jobs)
$domainesResult = $pdo->query("SELECT domaine, COUNT(*) as total FROM OpportuniteTravail WHERE domaine IS NOT NULL AND domaine != '' GROUP BY domaine");
$domaines = $domainesResult->fetchAll();

// Répartition par contrat (pour graphique - Jobs)
$contratsResult = $pdo->query("SELECT type_contrat, COUNT(*) as total FROM OpportuniteTravail WHERE type_contrat IS NOT NULL AND type_contrat != '' GROUP BY type_contrat");
$contrats = $contratsResult->fetchAll();

// Répartition par Ville (pour graphique - Stages)
$villesResult = $pdo->query("SELECT ville, COUNT(*) as total FROM Stage WHERE ville IS NOT NULL AND ville != '' GROUP BY ville");
$villes = $villesResult->fetchAll();

// Offres
$travaux = $pdo->query("SELECT * FROM OpportuniteTravail ORDER BY id DESC")->fetchAll();
$stages = $pdo->query("SELECT * FROM Stage ORDER BY id DESC")->fetchAll();

// JSON pour Chart.js
$domainesLabels = json_encode(array_column($domaines, 'domaine'));
$domainesData   = json_encode(array_column($domaines, 'total'));
$contratsLabels = json_encode(array_column($contrats, 'type_contrat'));
$contratsData   = json_encode(array_column($contrats, 'total'));
$villesLabels   = json_encode(array_column($villes, 'ville'));
$villesData     = json_encode(array_column($villes, 'total'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Les Offres – Backend | Career Lab</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <link rel="icon" href="../assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

  <!-- Fonts and icons -->
  <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
  <script>
    WebFont.load({
      google: { families: ["Public Sans:300,400,500,600,700"] },
      custom: {
        families: ["Font Awesome 5 Solid","Font Awesome 5 Regular","Font Awesome 5 Brands","simple-line-icons"],
        urls: ["../assets/css/fonts.min.css"],
      },
      active: function () { sessionStorage.fonts = true; },
    });
  </script>

  <!-- CSS -->
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/css/plugins.min.css" />
  <link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />
  <link rel="stylesheet" href="../assets/css/demo.css" />

  <style>
    .stat-card { border-radius: 16px; border: none; transition: transform 0.2s, box-shadow 0.2s; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.12); }
    .stat-icon { font-size: 2.2rem; opacity: 0.9; }
    .card-purple { background: linear-gradient(135deg, #6366f1, #a855f7); color: #fff; }
    .card-blue { background: linear-gradient(135deg, #3b82f6, #06b6d4); color: #fff; }
    .card-green { background: linear-gradient(135deg, #10b981, #34d399); color: #fff; }
    .card-orange { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #fff; }
    .badge-travail { background: #6366f1; }
    .badge-stage   { background: #0ea5e9; }
    .chart-container { position: relative; height: 300px; }
    .table-responsive { border-radius: 12px; }
  </style>
</head>
<body>
<div class="wrapper">

  <!-- ===================== SIDEBAR ===================== -->
  <div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
      <div class="logo-header" data-background-color="dark">
        <a href="../components/avatars.html">
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
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
              <i class="fas fa-home"></i><p>Dashboard</p><span class="caret"></span>
            </a>
            <div class="collapse" id="dashboard">
              <ul class="nav nav-collapse">
                <li><a href="../index.html"><span class="sub-item">Dashboard 1</span></a></li>
              </ul>
            </div>
          </li>
          <li class="nav-section">
            <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
            <h4 class="text-section">Components</h4>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#base">
              <i class="fas fa-layer-group"></i><p>utilisateur</p><span class="caret"></span>
            </a>
            <div class="collapse" id="base">
              <ul class="nav nav-collapse">
                <li><a href="../components/avatars.html"><span class="sub-item">utilisateur</span></a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#sidebarLayouts">
              <i class="fas fa-th-list"></i><p>métiers</p><span class="caret"></span>
            </a>
            <div class="collapse" id="sidebarLayouts">
              <ul class="nav nav-collapse">
                <li><a href="../sidebar-style-2.html"><span class="sub-item">métiers</span></a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#forms">
              <i class="fas fa-pen-square"></i><p>evaluation</p><span class="caret"></span>
            </a>
            <div class="collapse" id="forms">
              <ul class="nav nav-collapse">
                <li><a href="../forms/forms.html"><span class="sub-item">evaluation</span></a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item active submenu">
            <a data-bs-toggle="collapse" href="#tables">
              <i class="fas fa-table"></i><p>les offres</p><span class="caret"></span>
            </a>
            <div class="collapse show" id="tables">
              <ul class="nav nav-collapse">
                <li class="active"><a href="tables.php"><span class="sub-item">les offres</span></a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#maps">
              <i class="fas fa-map-marker-alt"></i><p>E_learning</p><span class="caret"></span>
            </a>
            <div class="collapse" id="maps">
              <ul class="nav nav-collapse">
                <li><a href="../maps/googlemaps.html"><span class="sub-item">E_learning</span></a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#charts">
              <i class="far fa-chart-bar"></i><p>Charts</p><span class="caret"></span>
            </a>
            <div class="collapse" id="charts">
              <ul class="nav nav-collapse">
                <li><a href="../charts/charts.html"><span class="sub-item">Chart Js</span></a></li>
              </ul>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!-- ===================== END SIDEBAR ===================== -->

  <div class="main-panel">
    <div class="content">
      <div class="page-inner">

        <!-- Page Header -->
        <div id="notification" class="alert d-none mb-4 shadow-sm" style="border-radius: 12px; font-weight: 500;"></div>
        
        <div class="page-header">
          <h4 class="page-title">Les Offres</h4>
          <ul class="breadcrumbs">
            <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Tables</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Les Offres</a></li>
          </ul>
        </div>

        <!-- ========== STAT CARDS ========== -->
        <div class="row mb-4">
          <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card stat-card card-purple shadow-sm">
              <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="fas fa-briefcase stat-icon"></i>
                <div>
                  <div class="fw-bold fs-4"><?= $totalOffres ?></div>
                  <div class="small">Total Offres</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card stat-card card-blue shadow-sm">
              <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="fas fa-user-tie stat-icon"></i>
                <div>
                  <div class="fw-bold fs-4"><?= $totalTravail ?></div>
                  <div class="small">Offres Emploi</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card stat-card card-green shadow-sm">
              <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="fas fa-graduation-cap stat-icon"></i>
                <div>
                  <div class="fw-bold fs-4"><?= $totalStage ?></div>
                  <div class="small">Offres Stages</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card stat-card card-orange shadow-sm">
              <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="fas fa-paper-plane stat-icon"></i>
                <div>
                  <div class="fw-bold fs-4"><?= $totalCandidatures ?></div>
                  <div class="small">Postulations</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ========== RECENT CANDIDATURES ========== -->
        <div class="row mb-4">
          <div class="col-md-12">
            <div class="card shadow-sm">
              <div class="card-header bg-dark text-white">
                <h4 class="card-title mb-0 text-white">📩 Candidatures Récentes</h4>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>Nom</th><th>Email</th><th>Poste / Offre</th><th>Date</th><th>CV</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($recentCandidatures as $cand): ?>
                      <tr>
                        <td><strong><?= htmlspecialchars($cand['nom']) ?></strong></td>
                        <td><?= htmlspecialchars($cand['email']) ?></td>
                        <td>
                          <span class="badge <?= $cand['type_offre'] == 'travail' ? 'bg-primary' : 'bg-info' ?>">
                            <?= htmlspecialchars($cand['offre_titre'] ?? 'Offre supprimée') ?>
                          </span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($cand['date_candidature'])) ?></td>
                        <td>
                          <?php if ($cand['cv_filename']): ?>
                            <a href="../uploads/cv/<?= $cand['cv_filename'] ?>" target="_blank" class="btn btn-sm btn-link"><i class="fas fa-file-pdf"></i> Voir</a>
                          <?php else: ?>
                            <span class="text-muted small">Aucun</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ========== CHARTS ========== -->
        <div class="row mb-4">
          <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
              <div class="card-header border-0 pb-0"><h6 class="card-title mb-0">Domaines d'Emploi</h6></div>
              <div class="card-body">
                <div class="chart-container">
                  <canvas id="chartDomaines"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
              <div class="card-header border-0 pb-0"><h6 class="card-title mb-0">Types de Contrat</h6></div>
              <div class="card-body">
                <div class="chart-container">
                  <canvas id="chartContrats"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
              <div class="card-header border-0 pb-0"><h6 class="card-title mb-0">Villes de Stages</h6></div>
              <div class="card-body">
                <div class="chart-container">
                  <canvas id="chartVilles"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ========== TABLE OFFRES TRAVAIL ========== -->
        <div class="row">
          <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
              <div class="card-header">
                <div class="d-flex align-items-center">
                  <h4 class="card-title mb-0">📋 Opportunités de Travail</h4>
                  <a href="../offres.php" class="btn btn-primary btn-round ms-auto btn-sm">
                    <i class="fa fa-plus"></i> Ajouter
                  </a>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="tbl-travail" class="display table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>ID</th><th>Titre</th><th>Entreprise</th><th>Type Contrat</th>
                        <th>Domaine</th><th>Localisation</th><th>Date Publication</th><th>Statut</th>
                        <th style="width: 10%">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($travaux as $t): ?>
                      <tr>
                        <td><?= $t['id'] ?></td>
                        <td><?= htmlspecialchars($t['titre']) ?></td>
                        <td><?= htmlspecialchars($t['entreprise'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($t['type_contrat'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($t['domaine'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($t['localisation'] ?? '-') ?></td>
                        <td><?= $t['date_publication'] ?? '-' ?></td>
                        <td>
                          <?php if (!empty($t['date_expiration']) && $t['date_expiration'] >= date('Y-m-d')): ?>
                            <span class="badge bg-success">Active</span>
                          <?php else: ?>
                            <span class="badge bg-secondary">Expirée</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <div class="form-button-action">
                            <a href="../offres.php" class="btn btn-link btn-primary px-1" title="Modifier">
                              <i class="fa fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-link btn-danger px-1" title="Supprimer" onclick="confirmDelete(<?= $t['id'] ?>, 'travail')">
                              <i class="fa fa-times text-danger"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                      <?php if (empty($travaux)): ?>
                      <tr><td colspan="8" class="text-center text-muted py-4">Aucune offre d'emploi publiée.</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- ========== TABLE STAGES ========== -->
          <div class="col-md-12">
            <div class="card shadow-sm">
              <div class="card-header">
                <div class="d-flex align-items-center">
                  <h4 class="card-title mb-0">🎓 Stages</h4>
                  <a href="../offres.php" class="btn btn-info btn-round ms-auto btn-sm text-white">
                    <i class="fa fa-plus"></i> Ajouter
                  </a>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="tbl-stage" class="display table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>ID</th><th>Société</th><th>Ville</th><th>Durée</th>
                        <th>Niveau Étude</th><th>Date Début</th><th>Date Fin</th>
                        <th>Email</th><th>Statut</th>
                        <th style="width: 10%">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($stages as $s): ?>
                      <tr>
                        <td><?= $s['id'] ?></td>
                        <td><?= htmlspecialchars($s['nom_societe']) ?></td>
                        <td><?= htmlspecialchars($s['ville'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($s['duree'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($s['niveau_etude'] ?? '-') ?></td>
                        <td><?= $s['date_debut'] ?? '-' ?></td>
                        <td><?= $s['date_fin'] ?? '-' ?></td>
                        <td><?= htmlspecialchars($s['email_contact'] ?? '-') ?></td>
                        <td>
                          <?php if (($s['statut'] ?? 'disponible') === 'disponible'): ?>
                            <span class="badge bg-success">Disponible</span>
                          <?php else: ?>
                            <span class="badge bg-danger">Fermé</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <div class="form-button-action">
                            <a href="../offres.php" class="btn btn-link btn-info px-1" title="Modifier">
                              <i class="fa fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-link btn-danger px-1" title="Supprimer" onclick="confirmDelete(<?= $s['id'] ?>, 'stage')">
                              <i class="fa fa-times text-danger"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                      <?php if (empty($stages)): ?>
                      <tr><td colspan="9" class="text-center text-muted py-4">Aucun stage publié.</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div><!-- end row -->

      </div>
    </div>
  </div><!-- main-panel -->
</div><!-- wrapper -->

<!-- Core JS -->
<script src="../assets/js/core/jquery-3.7.1.min.js"></script>
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="../assets/js/plugin/chart.js/chart.min.js"></script>
<script src="../assets/js/plugin/datatables/datatables.min.js"></script>
<script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>
<script src="../assets/js/kaiadmin.min.js"></script>
<script src="../assets/js/setting-demo.js"></script>
<script src="../assets/js/demo.js"></script>

<script>
$(document).ready(function () {
  // DataTables
  $('#tbl-travail').DataTable({ pageLength: 5 });
  $('#tbl-stage').DataTable({ pageLength: 5 });

  // --- Graphique Domaines (Doughnut) ---
  const domainesLabels = <?= $domainesLabels ?>;
  const domainesData   = <?= $domainesData ?>;

  if (domainesLabels.length > 0) {
    new Chart(document.getElementById('chartDomaines'), {
      type: 'doughnut',
      data: {
        labels: domainesLabels,
        datasets: [{
          data: domainesData,
          backgroundColor: ['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'],
          borderWidth: 2
        }]
      },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } } }
    });
  }

  // --- Graphique Contrats (Bar) ---
  const contratsLabels = <?= $contratsLabels ?>;
  const contratsData   = <?= $contratsData ?>;

  if (contratsLabels.length > 0) {
    new Chart(document.getElementById('chartContrats'), {
      type: 'bar',
      data: {
        labels: contratsLabels,
        datasets: [{
          label: 'Offres Emploi',
          data: contratsData,
          backgroundColor: '#6366f1',
          borderRadius: 6,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1, color: '#94a3b8' }, grid: { drawBorder: false } }, x: { grid: { display: false } } }
      }
    });
  }

  // --- Graphique Villes Stages (PolarArea) ---
  const villesLabels = <?= $villesLabels ?>;
  const villesData   = <?= $villesData ?>;

  if (villesLabels.length > 0) {
    new Chart(document.getElementById('chartVilles'), {
      type: 'polarArea',
      data: {
        labels: villesLabels,
        datasets: [{
          data: villesData,
          backgroundColor: ['#10b981','#3b82f6','#8b5cf6','#f59e0b','#ef4444'],
          opacity: 0.8
        }]
      },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } } }
    });
  }
});

function confirmDelete(id, type) {
    swal({
        title: "Êtes-vous sûr ?",
        text: "Cette offre sera définitivement supprimée du système !",
        icon: "warning",
        buttons: {
            cancel: { visible: true, text: "Annuler", className: "btn btn-danger" },
            confirm: { text: "Oui, supprimer !", className: "btn btn-success" },
        },
    }).then((willDelete) => {
        if (willDelete) {
            window.location.href = "../index.php?action=delete&id=" + id + "&type=" + type + "&redirect=tables";
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const notif = document.getElementById('notification');
    
    const messages = {
        'travail': '✅ Offre de travail publiée avec succès !',
        'stage': '✅ Stage publié avec succès !',
        'update': '✅ Modification enregistrée avec succès !',
        'deleted': '🗑️ Offre supprimée avec succès.',
        'titre_requis': '❌ Le titre de l\'offre est obligatoire.',
        'entreprise_requise': '❌ Le nom de l\'entreprise est obligatoire.',
        'description_requise': '❌ La description est trop courte (min. 10 caractères).',
        'email_invalide': '❌ L\'adresse email saisie est invalide.',
        'tel_invalide': '❌ Le numéro de téléphone doit être numérique.',
        'dates_invalides': '❌ La date de fin doit être postérieure au début.',
        'localisation_requise': '❌ La ville ou localisation est obligatoire.',
        'error': '❌ Une erreur est survenue lors de l\'opération.'
    };

    if (params.has('success')) {
        const key = params.get('success');
        notif.textContent = messages[key] || messages['success'] || 'Opération réussie !';
        notif.classList.remove('d-none');
        notif.classList.add('alert-success');
    } else if (params.has('error')) {
        const key = params.get('error');
        notif.textContent = messages[key] || messages['error'] || 'Une erreur est survenue.';
        notif.classList.remove('d-none');
        notif.classList.add('alert-danger');
    }
});
</script>
</body>
</html>
