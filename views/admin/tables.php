<?php
require_once '../../models/Database.php';

$pdo = Database::getInstance()->getConnection();

// Statistiques Offres
$totalTravail  = $pdo->query("SELECT COUNT(*) FROM OpportuniteTravail")->fetchColumn();
$totalOffres   = $totalTravail;

// Répartition par domaine (pour graphique - Jobs)
$domainesResult = $pdo->query("SELECT domaine, COUNT(*) as total FROM OpportuniteTravail WHERE domaine IS NOT NULL AND domaine != '' GROUP BY domaine");
$domaines = $domainesResult->fetchAll();

// Répartition par contrat (pour graphique - Jobs)
$contratsResult = $pdo->query("SELECT type_contrat, COUNT(*) as total FROM OpportuniteTravail WHERE type_contrat IS NOT NULL AND type_contrat != '' GROUP BY type_contrat");
$contrats = $contratsResult->fetchAll();

// Offres
$travaux = $pdo->query("SELECT t.*, e.niveau as niveau_experience FROM OpportuniteTravail t LEFT JOIN Experience e ON t.experience_id = e.id ORDER BY t.id DESC")->fetchAll();

// Expériences
$experiences = $pdo->query("SELECT * FROM Experience ORDER BY id ASC")->fetchAll();

// JSON pour Chart.js
$domainesLabels = json_encode(array_column($domaines, 'domaine'));
$domainesData   = json_encode(array_column($domaines, 'total'));
$contratsLabels = json_encode(array_column($contrats, 'type_contrat'));
$contratsData   = json_encode(array_column($contrats, 'total'));

// Répartition par expérience
$expStatsResult = $pdo->query("SELECT e.niveau, COUNT(t.id) as total FROM Experience e LEFT JOIN OpportuniteTravail t ON e.id = t.experience_id GROUP BY e.id, e.niveau");
$expStats = $expStatsResult->fetchAll();
$expLabels = json_encode(array_column($expStats, 'niveau'));
$expData   = json_encode(array_column($expStats, 'total'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Gestion des Offres d'Emploi | Career Lab</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <link rel="icon" href="../../assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

  <!-- Fonts and icons -->
  <script src="../../assets/js/plugin/webfont/webfont.min.js"></script>
  <script>
    WebFont.load({
      google: { families: ["Public Sans:300,400,500,600,700"] },
      custom: {
        families: ["Font Awesome 5 Solid","Font Awesome 5 Regular","Font Awesome 5 Brands","simple-line-icons"],
        urls: ["../../assets/css/fonts.min.css"],
      },
      active: function () { sessionStorage.fonts = true; },
    });
  </script>

  <!-- CSS -->
  <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../../assets/css/plugins.min.css" />
  <link rel="stylesheet" href="../../assets/css/kaiadmin.min.css" />
  <link rel="stylesheet" href="../../assets/css/demo.css" />

  <style>
    .stat-card { border-radius: 16px; border: none; transition: transform 0.2s, box-shadow 0.2s; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.12); }
    .stat-icon { font-size: 2.2rem; opacity: 0.9; }
    .card-purple { background: linear-gradient(135deg, #6366f1, #a855f7); color: #fff; }
    .card-blue { background: linear-gradient(135deg, #3b82f6, #06b6d4); color: #fff; }
    .card-green { background: linear-gradient(135deg, #10b981, #34d399); color: #fff; }
    .card-orange { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #fff; }
    .badge-travail { background: #6366f1; }
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
        <a href="../../../index.php?action=offres">
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
            <a href="../../../index.php?action=offres">
              <i class="fas fa-arrow-left"></i><p>Retour au Site</p>
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

        <!-- Page Header -->
        <div id="notification" class="alert d-none mb-4 shadow-sm" style="border-radius: 12px; font-weight: 500;"></div>
        
        <div class="page-header">
          <div class="d-flex align-items-center">
            <h4 class="page-title">Les Offres</h4>
            <a href="../../../index.php?action=offres&q=" class="btn btn-primary btn-round ms-3 shadow-sm" style="font-weight: 900; font-size: 1.2rem; min-width: 45px;">
              <i class="fas fa-arrow-right"></i>
            </a>
          </div>
          <ul class="breadcrumbs">
            <li class="nav-home"><a href="../../../index.php?action=offres"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Admin</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Les Offres</a></li>
          </ul>
        </div>

        <!-- ========== STAT CARDS ========== -->
        <div class="row mb-4">
          <div class="col-sm-6 col-xl-4 mb-3">
            <div class="card stat-card card-purple shadow-sm">
              <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="fas fa-briefcase stat-icon"></i>
                <div>
                  <div class="fw-bold fs-4"><?= $totalOffres ?></div>
                  <div class="small">Total Opportunités</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-4 mb-3">
            <div class="card stat-card card-blue shadow-sm">
              <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="fas fa-user-tie stat-icon"></i>
                <div>
                  <div class="fw-bold fs-4"><?= $totalTravail ?></div>
                  <div class="small">Offres Publiées</div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <!-- ========== CHARTS ========== -->
        <div class="row mb-4">
          <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
              <div class="card-header border-0 pb-0"><h6 class="card-title mb-0">Répartition par Domaine</h6></div>
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
              <div class="card-header border-0 pb-0"><h6 class="card-title mb-0">Niveaux d'Expérience</h6></div>
              <div class="card-body">
                <div class="chart-container">
                  <canvas id="chartExperience"></canvas>
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
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="tbl-travail" class="display table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>ID</th><th>Titre</th><th>Entreprise</th><th>Contrat</th>
                        <th>ID Expérience</th><th>Domaine</th><th>Statut</th>
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
                        <td><span class="badge bg-info"><?= htmlspecialchars($t['niveau_experience'] ?? 'Non spécifié') ?></span></td>
                        <td><?= htmlspecialchars($t['domaine'] ?? '-') ?></td>
                        <td>
                          <?php if (!empty($t['date_expiration']) && $t['date_expiration'] >= date('Y-m-d')): ?>
                            <span class="badge bg-success">Active</span>
                          <?php else: ?>
                            <span class="badge bg-secondary">Expirée</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <div class="form-button-action">
                            <button type="button" class="btn btn-link btn-primary px-1" title="Modifier" 
                                    data-bs-toggle="modal" data-bs-target="#editRowModal"
                                    data-id="<?= $t['id'] ?>"
                                    data-titre="<?= htmlspecialchars($t['titre']) ?>"
                                    data-entreprise="<?= htmlspecialchars($t['entreprise'] ?? '') ?>"
                                    data-description="<?= htmlspecialchars($t['description'] ?? '') ?>"
                                    data-localisation="<?= htmlspecialchars($t['localisation'] ?? '') ?>"
                                    data-type_contrat="<?= htmlspecialchars($t['type_contrat'] ?? '') ?>"
                                    data-experience_id="<?= $t['experience_id'] ?? '' ?>"
                                    data-domaine="<?= htmlspecialchars($t['domaine'] ?? '') ?>"
                                    data-date_expiration="<?= $t['date_expiration'] ?? '' ?>"
                                    data-niveau_experience="<?= htmlspecialchars($t['niveau_experience'] ?? '') ?>">
                              <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-link btn-danger px-1" title="Supprimer" onclick="confirmDelete(<?= $t['id'] ?>, 'travail')">
                              <i class="fa fa-times text-danger"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div><!-- end row -->

        <!-- ========== TABLE EXPERIENCES ========== -->
        <div class="row">
          <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">🎓 Niveaux d'Expérience</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="display table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>ID</th><th>Niveau</th><th>Description</th><th style="width: 10%">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($experiences as $exp): ?>
                      <tr>
                        <td><?= $exp['id'] ?></td>
                        <td><?= htmlspecialchars($exp['niveau']) ?></td>
                        <td><?= htmlspecialchars($exp['description'] ?? '-') ?></td>
                        <td>
                          <div class="form-button-action">
                            <button type="button" class="btn btn-link btn-primary px-1" title="Modifier" 
                                    data-bs-toggle="modal" data-bs-target="#editExpModal"
                                    data-id="<?= $exp['id'] ?>"
                                    data-niveau="<?= htmlspecialchars($exp['niveau']) ?>"
                                    data-description="<?= htmlspecialchars($exp['description'] ?? '') ?>">
                              <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-link btn-danger px-1" title="Supprimer" onclick="confirmDelete(<?= $exp['id'] ?>, 'experience')">
                              <i class="fa fa-times text-danger"></i>
                            </button>
                          </div>
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

      </div>
    </div>
  </div><!-- main-panel -->
</div><!-- wrapper -->

<!-- Modal Ajout Expérience -->
<div class="modal fade" id="addExpModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">Ajouter un Niveau d'Expérience</h5>
        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
      </div>
      <form action="../../../index.php?action=publish" method="POST">
        <input type="hidden" name="type_offre" value="experience">
        <input type="hidden" name="redirect" value="tables">
        <div class="modal-body">
          <div class="form-group">
            <label>Niveau</label>
            <input type="text" name="niveau" class="form-control" placeholder="ex: Junior" required>
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" placeholder="Description du niveau"></textarea>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="submit" class="btn btn-primary">Ajouter</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Modification Offre -->
<div class="modal fade" id="editRowModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">
          <span class="fw-mediumbold">Modifier</span>
          <span class="fw-light"> l'opportunité</span>
        </h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="../../index.php?action=update" method="POST">
        <div class="modal-body">
          <input type="hidden" name="id" id="edit-id">
          <input type="hidden" name="type_offre" value="travail">
          <input type="hidden" name="redirect" value="tables">
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group form-group-default">
                <label>Titre</label>
                <input name="titre" id="edit-titre" type="text" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group form-group-default">
                <label>Entreprise</label>
                <input name="entreprise" id="edit-entreprise" type="text" class="form-control" required>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group form-group-default">
                <label>Description</label>
                <textarea name="description" id="edit-description" class="form-control" rows="3" required></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group form-group-default">
                <label>Localisation</label>
                <input name="localisation" id="edit-localisation" type="text" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group form-group-default">
                <label>Type de contrat</label>
                <input name="type_contrat" id="edit-type_contrat" type="text" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group form-group-default">
                <label>Domaine</label>
                <input name="domaine" id="edit-domaine" type="text" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group form-group-default">
                <label>ID Expérience</label>
                <select name="experience_id" id="edit-experience_id" class="form-control" required>
                    <?php foreach ($experiences as $exp): ?>
                        <option value="<?= $exp['id'] ?>"><?= $exp['id'] ?> - <?= htmlspecialchars($exp['niveau']) ?></option>
                    <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group form-group-default">
                <label>Date d'expiration</label>
                <input name="date_expiration" id="edit-expiration" type="date" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Modification Expérience -->
<div class="modal fade" id="editExpModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">Modifier le Niveau d'Expérience</h5>
        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
      </div>
      <form action="../../index.php?action=update" method="POST">
        <input type="hidden" name="id" id="edit-exp-id">
        <input type="hidden" name="type_offre" value="experience">
        <input type="hidden" name="redirect" value="tables">
        <div class="modal-body">
          <div class="form-group">
            <label>Niveau</label>
            <input type="text" name="niveau" id="edit-exp-niveau" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea name="description" id="edit-exp-description" class="form-control" rows="4"></textarea>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Core JS -->
<script src="../../assets/js/core/jquery-3.7.1.min.js"></script>
<script src="../../assets/js/core/popper.min.js"></script>
<script src="../../assets/js/core/bootstrap.min.js"></script>
<script src="../../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="../../assets/js/plugin/chart.js/chart.min.js"></script>
<script src="../../assets/js/plugin/datatables/datatables.min.js"></script>
<script src="../../assets/js/plugin/sweetalert/sweetalert.min.js"></script>
<script src="../../assets/js/kaiadmin.min.js"></script>

<script>
$(document).ready(function () {
  // --- Graphique Domaines (Doughnut) ---
  try {
    var dLabels = <?= $domainesLabels ?>;
    var dData   = <?= $domainesData ?>;
    if (typeof Chart !== 'undefined' && dLabels && dLabels.length > 0) {
      var ctxD = document.getElementById('chartDomaines');
      if (ctxD) {
        new Chart(ctxD, {
          type: 'doughnut',
          data: {
            labels: dLabels,
            datasets: [{
              data: dData,
              backgroundColor: ['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'],
              borderWidth: 2
            }]
          },
          options: { 
            responsive: true, 
            maintainAspectRatio: false, 
            legend: { 
              position: 'bottom', 
              labels: { boxWidth: 12, padding: 10 } 
            } 
          }
        });
      }
    }
  } catch (e) { console.error("Error init Chart Domaines:", e); }

  // --- Graphique Contrats (Bar) ---
  try {
    var cLabels = <?= $contratsLabels ?>;
    var cData   = <?= $contratsData ?>;
    if (typeof Chart !== 'undefined' && cLabels && cLabels.length > 0) {
      var ctxC = document.getElementById('chartContrats');
      if (ctxC) {
        new Chart(ctxC, {
          type: 'bar',
          data: {
            labels: cLabels,
            datasets: [{
              label: 'Offres Emploi',
              data: cData,
              backgroundColor: '#6366f1',
              borderRadius: 6,
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: { 
              yAxes: [{ 
                ticks: { beginAtZero: true, stepSize: 1, fontColor: '#94a3b8' }, 
                gridLines: { drawBorder: false } 
              }], 
              xAxes: [{ gridLines: { display: false } }] 
            }
          }
        });
      }
    }
  } catch (e) { console.error("Error init Chart Contrats:", e); }
 
  // --- Graphique Expérience (Doughnut - Affichage des Niveaux) ---
  try {
    var eLabels = <?= $expLabels ?>;
    var eDataReal = <?= $expData ?>; // Données réelles des offres
    
    // Création de parts égales (1 pour chaque niveau) pour que tout soit visible
    var eDataConstant = eLabels.map(() => 1);
    
    if (typeof Chart !== 'undefined' && eLabels && eLabels.length > 0) {
      var ctxE = document.getElementById('chartExperience');
      if (ctxE) {
        new Chart(ctxE, {
          type: 'doughnut',
          data: {
            // Ajout du nombre d'offres dans le nom pour la légende
            labels: eLabels.map((lbl, i) => lbl + " (" + eDataReal[i] + ")"), 
            datasets: [{
              data: eDataConstant, // Parts égales
              backgroundColor: ['#6366f1','#f59e0b','#10b981','#ef4444','#0ea5e9','#8b5cf6','#ec4899'],
              borderWidth: 2
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var index = tooltipItem.index;
                        var level = eLabels[index];
                        var count = eDataReal[index];
                        return level + " : " + count + " offre(s)";
                    }
                }
            }
          }
        });
      }
    }
  } catch (e) { console.error("Error init Chart Expérience:", e); }

  // --- DataTables ---
  try {
    if ($.fn.DataTable) {
      $('#tbl-travail').DataTable({ pageLength: 10 });
    }
  } catch (e) { console.error("Error init DataTables:", e); }
});

function confirmDelete(id, type) {
    if (typeof swal !== 'undefined') {
        swal({
            title: "Êtes-vous sûr ?",
            text: "Cette offre sera définitivement supprimée !",
            icon: "warning",
            buttons: {
                cancel: { visible: true, text: "Annuler", className: "btn btn-danger" },
                confirm: { text: "Oui, supprimer !", className: "btn btn-success" },
            },
        }).then((willDelete) => {
            if (willDelete) {
                window.location.href = "../../../index.php?action=delete&id=" + id + "&type=" + type + "&redirect=tables";
            }
        });
    } else {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette offre ?")) {
            window.location.href = "../../../index.php?action=delete&id=" + id + "&type=" + type + "&redirect=tables";
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const notif = document.getElementById('notification');
    
    const messages = {
        'travail': '✅ Offre publiée avec succès !',
        'update': '✅ Modification enregistrée avec succès !',
        'deleted': '🗑️ Offre supprimée avec succès.',
        'error': '❌ Une erreur est survenue.'
    };

    if (params.has('success')) {
        const key = params.get('success');
        notif.textContent = messages[key] || 'Opération réussie !';
        notif.classList.remove('d-none');
        notif.classList.add('alert-success');
    } else if (params.has('error')) {
        notif.textContent = messages['error'];
        notif.classList.remove('d-none');
        notif.classList.add('alert-danger');
    }
});

// Logic pour remplir le modal de modification
$(document).ready(function() {
    $('#editRowModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        $('#edit-id').val(button.data('id'));
        $('#edit-titre').val(button.data('titre'));
        $('#edit-entreprise').val(button.data('entreprise'));
        $('#edit-description').val(button.data('description'));
        $('#edit-localisation').val(button.data('localisation'));
        $('#edit-type_contrat').val(button.data('type_contrat'));
        $('#edit-domaine').val(button.data('domaine'));
        $('#edit-experience_id').val(button.data('experience_id'));
        $('#edit-expiration').val(button.data('date_expiration'));
    });

    $('#editExpModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        $('#edit-exp-id').val(button.data('id'));
        $('#edit-exp-niveau').val(button.data('niveau'));
        $('#edit-exp-description').val(button.data('description'));
    });
});
</script>
</body>
</html>
