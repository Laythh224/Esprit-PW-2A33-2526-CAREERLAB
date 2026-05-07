<?php
$activeAccountPage = 'offres';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Gestion des Offres d'Emploi | Career Lab</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  
  <!-- Fonts and icons -->
  <script src="views/assets/js/webfont.min.js"></script>
  <script>
    WebFont.load({
      google: { families: ["Public Sans:300,400,500,600,700"] },
      custom: {
        families: ["Font Awesome 5 Solid","Font Awesome 5 Regular","Font Awesome 5 Brands","simple-line-icons"],
        urls: ["views/assets/css/fonts.min.css"],
      },
      active: function () { sessionStorage.fonts = true; },
    });
  </script>

  <!-- CSS -->
  <link rel="stylesheet" href="views/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="views/assets/css/plugins.min.css" />
  <link rel="stylesheet" href="views/assets/css/kaiadmin.min.css" />

  <style>
    .stat-card { border-radius: 16px; border: none; transition: transform 0.2s, box-shadow 0.2s; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.12); }
    .stat-icon { font-size: 2.2rem; opacity: 0.9; }
    .card-purple { background: linear-gradient(135deg, #6366f1, #a855f7); color: #fff; }
    .card-blue { background: linear-gradient(135deg, #3b82f6, #06b6d4); color: #fff; }
    .card-green { background: linear-gradient(135deg, #10b981, #34d399); color: #fff; }
    .badge-travail { background: #6366f1; }
    .table-responsive { border-radius: 12px; }
  </style>
</head>
<body>
<div class="wrapper">
  <!-- Sidebar -->
  <?php 
  $sidebarPath = __DIR__ . '/components/account-sidebar.php';
  if (is_file($sidebarPath)) {
      include $sidebarPath;
  }
  ?>

  <div class="main-panel">
    <div class="content">
      <div class="page-inner">
        <div class="page-header">
          <div class="d-flex align-items-center">
            <h4 class="page-title">Les Offres</h4>
            <a href="index.php?page=offres" class="btn btn-primary btn-round ms-3 shadow-sm" style="font-weight: 900; font-size: 1.2rem; min-width: 45px;">
              <i class="fas fa-arrow-right"></i>
            </a>
          </div>
          <ul class="breadcrumbs">
            <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item">Admin</li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item">Offres</li>
          </ul>
        </div>

        <!-- STAT CARDS -->
        <div class="row mb-4">
          <div class="col-sm-6 col-xl-4 mb-3">
            <div class="card stat-card card-purple shadow-sm">
              <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="fas fa-briefcase stat-icon"></i>
                <div>
                  <div class="fw-bold fs-4"><?= count($offres ?? []) ?></div>
                  <div class="small">Offres Publiées</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-4 mb-3">
            <div class="card stat-card card-blue shadow-sm">
              <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="fas fa-user-tie stat-icon"></i>
                <div>
                  <div class="fw-bold fs-4"><?= count($experiences ?? []) ?></div>
                  <div class="small">Niveaux d'Expérience</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-4 mb-3">
            <div class="card stat-card card-green shadow-sm">
              <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="fas fa-file-alt stat-icon"></i>
                <div>
                  <div class="fw-bold fs-4"><?= count($candidatures ?? []) ?></div>
                  <div class="small">Candidatures IA</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TABLE OFFRES TRAVAIL -->
        <div class="row">
          <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
              <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                  <h4 class="card-title mb-0">📋 Opportunités de Travail</h4>
                  <button class="btn btn-primary btn-round btn-sm" data-bs-toggle="modal" data-bs-target="#addOffreModal">
                    <i class="fa fa-plus"></i> Ajouter
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Titre</th><th>Entreprise</th><th>Contrat</th><th>Domaine</th><th style="width: 10%">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($offres)): ?>
                          <?php foreach ($offres as $t): ?>
                          <tr>
                            <td><?= htmlspecialchars($t['titre']) ?></td>
                            <td><?= htmlspecialchars($t['entreprise']) ?></td>
                            <td><?= htmlspecialchars($t['type_contrat'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($t['domaine'] ?? '-') ?></td>
                            <td>
                              <div class="form-button-action">
                                <a href="index.php?page=admin-offre-delete&id=<?= $t['id'] ?>" class="btn btn-link btn-danger" onclick="return confirm('Supprimer ?')">
                                  <i class="fa fa-times"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <tr><td colspan="5" class="text-center">Aucune offre trouvée.</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TABLE CANDIDATURES IA -->
        <div class="row">
          <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
              <div class="card-header">
                <h4 class="card-title mb-0">🤖 Candidatures & Analyse IA</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover align-middle">
                    <thead class="bg-light">
                      <tr>
                        <th>Candidat</th><th>Offre</th><th>Score IA</th><th>Recommandation</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($candidatures)): ?>
                          <?php foreach ($candidatures as $c): ?>
                          <tr>
                            <td>
                              <div class="fw-bold"><?= htmlspecialchars($c['nom_candidat']) ?></div>
                              <div class="small text-muted"><?= htmlspecialchars($c['email_candidat']) ?></div>
                            </td>
                            <td><?= htmlspecialchars($c['offre_titre']) ?></td>
                            <td>
                              <div class="d-flex align-items-center gap-2">
                                 <span class="fw-bold"><?= $c['score_ia'] ?>%</span>
                                 <div class="progress" style="width: 50px; height: 5px;">
                                    <div class="progress-bar" style="width: <?= $c['score_ia'] ?>%"></div>
                                 </div>
                              </div>
                            </td>
                            <td>
                              <?php 
                              $rec = $c['recommandation'] ?? 'Refuser';
                              $badgeColor = $rec === 'Accepter' ? 'success' : ($rec === 'À revoir' ? 'warning' : 'danger');
                              ?>
                              <span class="badge bg-<?= $badgeColor ?>"><?= $rec ?></span>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <tr><td colspan="4" class="text-center">Aucune candidature pour le moment.</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Modal Ajout -->
<div class="modal fade" id="addOffreModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"><h5>Nouvelle Offre</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="index.php?page=admin-offre-add" method="POST">
        <div class="modal-body">
           <div class="row g-3">
             <div class="col-md-6"><label>Titre</label><input type="text" name="titre" class="form-control" required></div>
             <div class="col-md-6"><label>Entreprise</label><input type="text" name="entreprise" class="form-control" required></div>
             <div class="col-12"><label>Description</label><textarea name="description" class="form-control" rows="3" required></textarea></div>
             <div class="col-md-6"><label>Localisation</label><input type="text" name="localisation" class="form-control"></div>
             <div class="col-md-6"><label>Contrat</label><input type="text" name="type_contrat" class="form-control"></div>
             <div class="col-md-6">
               <label>Niveau</label>
               <select name="experience_id" class="form-select" required>
                 <?php if (!empty($experiences)): ?>
                     <?php foreach ($experiences as $exp): ?>
                       <option value="<?= $exp['id'] ?>"><?= htmlspecialchars($exp['niveau']) ?></option>
                     <?php endforeach; ?>
                 <?php endif; ?>
               </select>
             </div>
             <div class="col-md-6"><label>Domaine</label><input type="text" name="domaine" class="form-control"></div>
           </div>
        </div>
        <div class="modal-footer"><button type="submit" class="btn btn-primary">Publier</button></div>
      </form>
    </div>
  </div>
</div>

<script src="views/assets/js/jquery-3.7.1.min.js"></script>
<script src="views/assets/js/bootstrap.min.js"></script>
<script src="views/assets/js/kaiadmin.min.js"></script>
</body>
</html>
