<?php
require_once 'config/Database.php';

$pdo = Database::getInstance()->getConnection();

// Filtre de recherche
$filtre = $_GET['filtre'] ?? 'all';
$search = trim($_GET['q'] ?? '');

// Offres de travail
$sqlTravail = "SELECT *, 'travail' AS type_offre FROM OpportuniteTravail WHERE 1=1";
if ($search) $sqlTravail .= " AND (titre LIKE :q OR entreprise LIKE :q OR domaine LIKE :q OR localisation LIKE :q)";
$sqlTravail .= " ORDER BY id DESC";

// Stages
$sqlStage = "SELECT *, 'stage' AS type_offre FROM Stage WHERE statut='disponible'";
if ($search) $sqlStage .= " AND (nom_societe LIKE :q OR ville LIKE :q OR description LIKE :q)";
$sqlStage .= " ORDER BY id DESC";

$stmtT = $pdo->prepare($sqlTravail);
$stmtS = $pdo->prepare($sqlStage);
if ($search) {
    $stmtT->bindValue(':q', "%$search%");
    $stmtS->bindValue(':q', "%$search%");
}
$stmtT->execute();
$stmtS->execute();
$travaux = $stmtT->fetchAll();
$stages  = $stmtS->fetchAll();

$totalTravail = count($travaux);
$totalStage   = count($stages);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Les Offres – Career Lab</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="description" content="Parcourez les offres d'emploi et de stages disponibles sur Career Lab.">

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <style>
    html, body {
      min-height: 100%;
      background: linear-gradient(180deg, #d3e5ff 0%, #96bbff 45%, #5d8df5 100%);
      background-attachment: fixed;
      color: #0f172a;
    }
    #spinner { display: none !important; }
    .container-fluid { background: transparent; }

    /* --- Form Section (from testimonial) --- */
    .form-section { padding: 4rem 0 2rem; }
    .form-card {
      background: rgba(255, 255, 255, 0.94);
      border: 1px solid rgba(15, 23, 42, 0.12);
      border-radius: 2rem;
      box-shadow: 0 28px 75px rgba(15, 23, 42, 0.08);
      padding: 2.5rem;
      margin-bottom: 3rem;
    }
    .form-card h3 { font-size: 1.8rem; margin-bottom: 0.5rem; }
    .form-card .form-label { font-weight: 600; color: #1f2937; }
    .form-card .form-control, .form-card .form-select {
      border-radius: 1rem;
      border: 1px solid rgba(15, 23, 42, 0.16);
      padding: 1rem 1.2rem;
    }
    .form-card .btn { border-radius: 1rem; padding: 1rem 1.5rem; font-weight: 700; transition: transform 0.2s; }
    .form-card .btn-primary { background: linear-gradient(135deg, #5c7cfa, #6d8dff); border: none; }
    .form-card .btn-success { background: linear-gradient(135deg, #22c55e, #16a34a); border: none; }
    .form-card .btn:hover { transform: translateY(-2px); }
    .option-card {
      background: rgba(59, 130, 246, 0.08);
      border: 1px solid rgba(59, 130, 246, 0.14);
      border-radius: 1.25rem;
      padding: 1rem 1.2rem;
      color: #1e3a8a;
    }
    .hidden { display: none !important; }
    .alert-box {
      border-radius: 1rem;
      padding: 1rem 1.4rem;
      font-weight: 600;
      font-size: 0.95rem;
      animation: fadeIn 0.4s ease;
    }
    .alert-success { background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.3); color: #15803d; }
    .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); color: #b91c1c; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

    /* --- Listing Section (Original) --- */
    .offres-hero { padding: 3rem 0 1.5rem; text-align: center; }
    .offres-hero h1 { font-size: 2.6rem; font-weight: 800; color: #fff; text-shadow: 0 2px 12px rgba(0,0,0,.15); }
    .offres-hero p  { color: rgba(255,255,255,.85); font-size: 1.1rem; }
    .search-bar { background: rgba(255,255,255,.92); border-radius: 2rem; box-shadow: 0 8px 32px rgba(15,23,42,.1); padding: 1.5rem 2rem; margin-bottom: 2rem; }
    .search-bar .form-control { border-radius: 1rem; border: 1.5px solid rgba(15,23,42,.14); padding: .85rem 1.2rem; }
    .search-bar .btn { border-radius: 1rem; padding: .85rem 1.8rem; font-weight: 700; }
    .filter-tabs { display: flex; gap: .6rem; flex-wrap: wrap; justify-content: center; margin-bottom: 2rem; }
    .filter-tabs .btn { border-radius: 2rem; font-weight: 600; padding: .5rem 1.4rem; transition: all .2s; }
    .offer-card {
      background: rgba(255,255,255,.94);
      border: 1px solid rgba(15,23,42,.1);
      border-radius: 1.5rem;
      box-shadow: 0 8px 32px rgba(15,23,42,.07);
      padding: 1.6rem;
      transition: transform .25s, box-shadow .25s;
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .offer-card:hover { transform: translateY(-5px); box-shadow: 0 20px 50px rgba(15,23,42,.13); }
    .offer-card .badge-type { font-size: .75rem; font-weight: 700; border-radius: .6rem; padding: .35rem .8rem; }
    .badge-travail { background: #4f46e5; color: #fff; }
    .badge-stage   { background: #0ea5e9; color: #fff; }
    .badge-statut-dispo { background: #d1fae5; color: #065f46; font-size:.72rem; border-radius:.5rem; padding:.2rem .7rem; }
    .badge-statut-ferme { background: #fee2e2; color: #991b1b; font-size:.72rem; border-radius:.5rem; padding:.2rem .7rem; }
    .offer-card h5 { font-size: 1.1rem; font-weight: 700; margin: .7rem 0 .4rem; color: #1e293b; }
    .offer-card .company { font-weight: 600; color: #4f46e5; font-size: .95rem; }
    .offer-card .meta    { font-size: .83rem; color: #64748b; }
    .offer-card .meta i  { margin-right: .3rem; }
    .offer-card .description { font-size: .88rem; color: #475569; flex-grow: 1; margin: .6rem 0; line-height: 1.6; }
    .offer-card .btn-postuler { border-radius: 1rem; font-weight: 700; margin-top: auto; }
    .empty-state { text-align: center; padding: 4rem 1rem; color: rgba(255,255,255,.75); }
    .empty-state i { font-size: 3rem; margin-bottom: 1rem; }
    .section-title { font-size: 1.3rem; font-weight: 800; color: #fff; margin: 2rem 0 1rem; padding-left: .5rem; border-left: 4px solid rgba(255,255,255,.6); }
    .modal-content { border-radius: 1.5rem; }
    .modal-header  { border-bottom: none; padding-bottom: 0; }
    .modal-body .form-control, .modal-body .form-select { border-radius: .9rem; padding: .75rem 1rem; }
    .modal-body .form-label { font-weight: 600; font-size: .88rem; }
    .modal-footer { border-top: none; }

    .edit-mode-btn { border-radius: 1rem; font-weight: 700; padding: .6rem 1.5rem; transition: background 0.3s; }
    .btn-manage-toggle { background: #f59e0b; color: #fff; border: none; }
    .btn-manage-toggle:hover { background: #d97706; color: #fff; }
    .btn-manage-toggle.active { background: #ef4444; }

    .edit-controls { display: none; gap: 0.5rem; margin-top: 1rem; border-top: 1px solid rgba(0,0,0,0.05); pt-2; }
    .manage-active .edit-controls { display: flex; }
    
    .btn-edit-item { background: #3b82f6; color: #fff; border-radius: .6rem; border: none; padding: .4rem .8rem; font-size: .8rem; }
    .btn-delete-item { background: #ef4444; color: #fff; border-radius: .6rem; border: none; padding: .4rem .8rem; font-size: .8rem; }
    
    @media (max-width: 768px) {
      .offres-hero { padding: 2rem 0 1.5rem; }
      .offres-hero h1 { font-size: 1.8rem; }
    }
  </style>
</head>
<body>
<!-- Spinner -->
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"></div>

<!-- Topbar -->
<div class="container-fluid bg-dark px-5">
  <div class="row gx-0">
    <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
      <div class="d-inline-flex align-items-center" style="height:45px;">
        <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>123 Street, New York, USA</small>
        <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+012 345 6789</small>
        <small class="text-light"><i class="fa fa-envelope-open me-2"></i>info@example.com</small>
      </div>
    </div>
    <div class="col-lg-4 text-center text-lg-end">
      <div class="d-inline-flex align-items-center" style="height:45px;">
        <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-twitter fw-normal"></i></a>
        <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-facebook-f fw-normal"></i></a>
        <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-linkedin-in fw-normal"></i></a>
        <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-instagram fw-normal"></i></a>
        <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle" href=""><i class="fab fa-youtube fw-normal"></i></a>
      </div>
    </div>
  </div>
</div>

<!-- Navbar -->
<div class="container-fluid position-relative p-0">
  <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
    <a href="indexF.html" class="navbar-brand p-0">
      <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>Carrer Lab</h1>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
      <span class="fa fa-bars"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <div class="navbar-nav ms-auto py-0">
        <a href="indexF.html" class="nav-item nav-link">Home</a>
        <a href="about.html" class="nav-item nav-link">About</a>
        <div class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Blog</a>
          <div class="dropdown-menu m-0">
            <a href="blog.html" class="dropdown-item">Blog Grid</a>
            <a href="detail.html" class="dropdown-item">Blog Detail</a>
          </div>
        </div>
        <div class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">services</a>
          <div class="dropdown-menu m-0">
            <a href="price.html" class="dropdown-item">utilisateur</a>
            <a href="feature.html" class="dropdown-item">métiers</a>
            <a href="team.html" class="dropdown-item">evaluation</a>
            <a href="offres.php" class="dropdown-item fw-bold">les offres</a>
            <a href="quote.html" class="dropdown-item">E_learning</a>
          </div>
        </div>
        <a href="contact.html" class="nav-item nav-link">Contact</a>
      </div>
      <button type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i></button>
    </div>
  </nav>
</div>

<!-- Form Section (Publier une offre) -->
<div class="container-fluid form-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <div class="form-card">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h3>📢 Publier une offre</h3>
                            <p class="form-note">Choisissez le type d'offre et remplissez les champs pour une annonce propre et professionnelle.</p>
                        </div>
                        <div class="option-card text-center d-none d-md-block">
                            <span class="d-block fw-semibold mb-1">Design moderne</span>
                            <small>Champs arrondis, ombres subtiles et interactions nettes.</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Type d'offre</label>
                        <select id="type" class="form-select" onchange="toggleForm()">
                            <option value="">-- Choisir --</option>
                            <option value="travail">Opportunité de travail</option>
                            <option value="stage">Stage</option>
                        </select>
                    </div>

                    <!-- Notification de publication -->
                    <div id="notif-publication" class="hidden alert-box mb-3"></div>

                    <div id="travailForm" class="hidden">
                        <form action="index.php?action=publish" method="POST">
                            <input type="hidden" name="type_offre" value="travail">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Titre</label>
                                    <input type="text" class="form-control" name="titre" placeholder="Titre de l'offre">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Entreprise</label>
                                    <input type="text" class="form-control" name="entreprise" placeholder="Entreprise">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3" placeholder="Description de l'offre"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Localisation</label>
                                    <input type="text" class="form-control" name="localisation" placeholder="Localisation/Ville">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Type de contrat</label>
                                    <input type="text" class="form-control" name="type_contrat" placeholder="Ex: CDI, CDD, Freelance...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Niveau d'expérience</label>
                                    <input type="text" class="form-control" name="niveau_experience" placeholder="Ex: Junior, Senior...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Domaine</label>
                                    <input type="text" class="form-control" name="domaine" placeholder="Domaine d'activité">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date d'expiration</label>
                                    <input type="date" class="form-control" name="date_expiration">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100 shadow-sm">Publier Offre Travail</button>
                            </div>
                        </form>
                    </div>

                    <div id="stageForm" class="hidden">
                        <form action="index.php?action=publish" method="POST">
                            <input type="hidden" name="type_offre" value="stage">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom de la société</label>
                                    <input type="text" class="form-control" name="nom_societe" placeholder="Nom de la société">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email de contact</label>
                                    <input type="email" class="form-control" name="email_contact" placeholder="Email">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" name="telephone" placeholder="Numéro de téléphone">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Adresse</label>
                                    <input type="text" class="form-control" name="adresse" placeholder="Adresse physique">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description du stage</label>
                                    <textarea class="form-control" name="description" rows="3" placeholder="Détails du stage"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Ville</label>
                                    <input type="text" class="form-control" name="ville" placeholder="Ville">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Durée</label>
                                    <input type="text" class="form-control" name="duree" placeholder="Ex: 3 mois">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Niveau d'étude</label>
                                    <input type="text" class="form-control" name="niveau_etude" placeholder="Ex: Licence...">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Date de début</label>
                                    <input type="date" class="form-control" name="date_debut">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Date de fin</label>
                                    <input type="date" class="form-control" name="date_fin">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Statut</label>
                                    <select class="form-select" name="statut">
                                        <option value="disponible">Disponible</option>
                                        <option value="rempli">Rempli / Fermé</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-success w-100 shadow-sm">Publier Stage</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Selection Section (Hero) -->
<div class="offres-hero mt-0 pt-0">
  <div class="container">
    <h1>🔍 Trouvez votre opportunité</h1>
    <p><?= $totalTravail + $totalStage ?> offre<?= ($totalTravail + $totalStage) > 1 ? 's' : '' ?> disponible<?= ($totalTravail + $totalStage) > 1 ? 's' : '' ?> — Emplois &amp; Stages</p>
  </div>
</div>

<!-- Main Content (Listing) -->
<div class="container pb-5">
  <!-- Notification de postulation (candidature) -->
  <div id="notification" class="hidden alert-box mb-4"></div>

  <!-- Search Bar -->
  <form method="GET" action="offres.php" class="search-bar">
    <div class="row g-2 align-items-center">
      <div class="col-md-10">
        <input type="text" name="q" class="form-control" placeholder="🔎  Rechercher par titre, entreprise, domaine, ville..." value="<?= htmlspecialchars($search) ?>">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Rechercher</button>
      </div>
    </div>
  </form>

  <!-- Toggle Edit Mode Button -->
  <div class="text-center mb-4">
    <button id="toggleEditMode" class="btn btn-manage-toggle edit-mode-btn shadow-sm">
      <i class="fas fa-edit me-2"></i>Gérer mes publications (Modifier/Supprimer)
    </button>
  </div>

  <!-- Filter Tabs -->
  <div class="filter-tabs">
    <a href="offres.php<?= $search ? '?q='.urlencode($search) : '' ?>" class="btn <?= $filtre === 'all' ? 'btn-light' : 'btn-outline-light' ?>">
      🌐 Tout (<?= $totalTravail + $totalStage ?>)
    </a>
    <a href="offres.php?filtre=travail<?= $search ? '&q='.urlencode($search) : '' ?>" class="btn <?= $filtre === 'travail' ? 'btn-light' : 'btn-outline-light' ?>">
      💼 Emplois (<?= $totalTravail ?>)
    </a>
    <a href="offres.php?filtre=stage<?= $search ? '&q='.urlencode($search) : '' ?>" class="btn <?= $filtre === 'stage' ? 'btn-light' : 'btn-outline-light' ?>">
      🎓 Stages (<?= $totalStage ?>)
    </a>
  </div>

  <!-- ===== OFFRES TRAVAIL ===== -->
  <?php if ($filtre !== 'stage'): ?>
    <div class="section-title">💼 Opportunités d'Emploi</div>
    <?php if (empty($travaux)): ?>
      <div class="empty-state"><i class="fas fa-briefcase"></i><p>Aucune offre d'emploi disponible.</p></div>
    <?php else: ?>
    <div class="row g-4 mb-4">
      <?php foreach ($travaux as $t): ?>
      <div class="col-md-6 col-lg-4">
        <div class="offer-card">
          <div class="d-flex justify-content-between align-items-center">
            <span class="badge-type badge-travail">Emploi</span>
            <?php if (!empty($t['type_contrat'])): ?>
              <span class="badge bg-light text-dark border"><?= htmlspecialchars($t['type_contrat']) ?></span>
            <?php endif; ?>
          </div>
          <h5><?= htmlspecialchars($t['titre']) ?></h5>
          <?php if (!empty($t['entreprise'])): ?>
            <div class="company"><i class="fas fa-building me-1"></i><?= htmlspecialchars($t['entreprise']) ?></div>
          <?php endif; ?>
          <div class="meta mt-1">
            <?php if (!empty($t['localisation'])): ?><span><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($t['localisation']) ?></span><?php endif; ?>
            <?php if (!empty($t['domaine'])): ?> &nbsp;|&nbsp; <span><i class="fas fa-tags"></i><?= htmlspecialchars($t['domaine']) ?></span><?php endif; ?>
            <?php if (!empty($t['niveau_experience'])): ?> &nbsp;|&nbsp; <span><i class="fas fa-layer-group"></i><?= htmlspecialchars($t['niveau_experience']) ?></span><?php endif; ?>
          </div>
          <?php if (!empty($t['description'])): ?>
            <div class="description"><?= htmlspecialchars(substr($t['description'], 0, 130)) ?><?= strlen($t['description']) > 130 ? '...' : '' ?></div>
          <?php endif; ?>
          <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
            <?php if (!empty($t['date_expiration'])): ?>
              <small class="text-muted">Expire: <?= $t['date_expiration'] ?></small>
            <?php else: ?><span></span><?php endif; ?>
          </div>
          <!-- Edit Controls -->
          <div class="edit-controls">
            <button class="btn-edit-item w-100" 
                    data-bs-toggle="modal" data-bs-target="#editModal"
                    data-type="travail"
                    data-id="<?= $t['id'] ?>"
                    data-titre="<?= htmlspecialchars($t['titre']) ?>"
                    data-entreprise="<?= htmlspecialchars($t['entreprise'] ?? '') ?>"
                    data-description="<?= htmlspecialchars($t['description'] ?? '') ?>"
                    data-localisation="<?= htmlspecialchars($t['localisation'] ?? '') ?>"
                    data-type_contrat="<?= htmlspecialchars($t['type_contrat'] ?? '') ?>"
                    data-date_expiration="<?= $t['date_expiration'] ?? '' ?>"
                    data-niveau_experience="<?= htmlspecialchars($t['niveau_experience'] ?? '') ?>"
                    data-domaine="<?= htmlspecialchars($t['domaine'] ?? '') ?>">
              <i class="fas fa-pen me-1"></i> Modifier
            </button>
            <button class="btn-delete-item w-100" onclick="confirmDelete(<?= $t['id'] ?>, 'travail')">
              <i class="fas fa-trash me-1"></i> Supprimer
            </button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  <?php endif; ?>

  <!-- ===== STAGES ===== -->
  <?php if ($filtre !== 'travail'): ?>
    <div class="section-title">🎓 Stages</div>
    <?php if (empty($stages)): ?>
      <div class="empty-state"><i class="fas fa-graduation-cap"></i><p>Aucun stage disponible.</p></div>
    <?php else: ?>
    <div class="row g-4">
      <?php foreach ($stages as $s): ?>
      <div class="col-md-6 col-lg-4">
        <div class="offer-card">
          <div class="d-flex justify-content-between align-items-center">
            <span class="badge-type badge-stage">Stage</span>
            <?php if (($s['statut'] ?? 'disponible') === 'disponible'): ?>
              <span class="badge-statut-dispo">Disponible</span>
            <?php else: ?>
              <span class="badge-statut-ferme">Rempli/Fermé</span>
            <?php endif; ?>
          </div>
          <h5><?= htmlspecialchars($s['nom_societe']) ?></h5>
          <div class="meta mt-1">
            <?php if (!empty($s['ville'])): ?><span><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($s['ville']) ?></span><?php endif; ?>
            <?php if (!empty($s['duree'])): ?> &nbsp;|&nbsp; <span><i class="fas fa-clock"></i><?= htmlspecialchars($s['duree']) ?></span><?php endif; ?>
            <?php if (!empty($s['niveau_etude'])): ?> &nbsp;|&nbsp; <span><i class="fas fa-graduation-cap"></i><?= htmlspecialchars($s['niveau_etude']) ?></span><?php endif; ?>
          </div>
          <div class="meta mt-1 small">
             <?php if (!empty($s['adresse'])): ?><span><i class="fas fa-building"></i> <?= htmlspecialchars($s['adresse']) ?></span><?php endif; ?>
             <?php if (!empty($s['telephone'])): ?> &nbsp;|&nbsp; <span><i class="fas fa-phone"></i> <?= htmlspecialchars($s['telephone']) ?></span><?php endif; ?>
          </div>
          <?php if (!empty($s['description'])): ?>
            <div class="description"><?= htmlspecialchars(substr($s['description'], 0, 130)) ?><?= strlen($s['description']) > 130 ? '...' : '' ?></div>
          <?php endif; ?>
          <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
            <?php if (!empty($s['date_debut']) && !empty($s['date_fin'])): ?>
              <small class="text-muted"><?= $s['date_debut'] ?> → <?= $s['date_fin'] ?></small>
            <?php else: ?><span></span><?php endif; ?>
          </div>
          <!-- Edit Controls -->
          <div class="edit-controls">
            <button class="btn-edit-item w-100" 
                    data-bs-toggle="modal" data-bs-target="#editModal"
                    data-type="stage"
                    data-id="<?= $s['id'] ?>"
                    data-nom_societe="<?= htmlspecialchars($s['nom_societe']) ?>"
                    data-email_contact="<?= htmlspecialchars($s['email_contact'] ?? '') ?>"
                    data-telephone="<?= htmlspecialchars($s['telephone'] ?? '') ?>"
                    data-adresse="<?= htmlspecialchars($s['adresse'] ?? '') ?>"
                    data-description="<?= htmlspecialchars($s['description'] ?? '') ?>"
                    data-ville="<?= htmlspecialchars($s['ville'] ?? '') ?>"
                    data-duree="<?= htmlspecialchars($s['duree'] ?? '') ?>"
                    data-niveau_etude="<?= htmlspecialchars($s['niveau_etude'] ?? '') ?>"
                    data-date_debut="<?= $s['date_debut'] ?? '' ?>"
                    data-date_fin="<?= $s['date_fin'] ?? '' ?>"
                    data-statut="<?= $s['statut'] ?? 'disponible' ?>">
              <i class="fas fa-pen me-1"></i> Modifier
            </button>
            <button class="btn-delete-item w-100" onclick="confirmDelete(<?= $s['id'] ?>, 'stage')">
              <i class="fas fa-trash me-1"></i> Supprimer
            </button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  <?php endif; ?>

</div><!-- /container -->


<!-- ===== MODAL MODIFIER ===== -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">✏️ Modifier l'offre</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="index.php?action=update" method="POST">
        <div class="modal-body">
          <input type="hidden" name="id" id="edit-id">
          <input type="hidden" name="type_offre" id="edit-type">

          <!-- Form Travail -->
          <div id="editTravailFields" class="hidden">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Titre</label>
                <input type="text" class="form-control" name="titre" id="edit-titre">
              </div>
              <div class="col-md-6">
                <label class="form-label">Entreprise</label>
                <input type="text" class="form-control" name="entreprise" id="edit-entreprise">
              </div>
              <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3" id="edit-description-t"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label">Localisation</label>
                <input type="text" class="form-control" name="localisation" id="edit-localisation">
              </div>
              <div class="col-md-6">
                <label class="form-label">Type de contrat</label>
                <input type="text" class="form-control" name="type_contrat" id="edit-type-contrat">
              </div>
              <div class="col-md-6">
                <label class="form-label">Expérience</label>
                <input type="text" class="form-control" name="niveau_experience" id="edit-experience">
              </div>
              <div class="col-md-6">
                <label class="form-label">Domaine</label>
                <input type="text" class="form-control" name="domaine" id="edit-domaine">
              </div>
              <div class="col-md-6">
                <label class="form-label">Expiration</label>
                <input type="date" class="form-control" name="date_expiration" id="edit-expiration">
              </div>
            </div>
          </div>

          <!-- Form Stage -->
          <div id="editStageFields" class="hidden">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Société</label>
                <input type="text" class="form-control" name="nom_societe" id="edit-nom-societe">
              </div>
              <div class="col-md-6">
                <label class="form-label">Email contact</label>
                <input type="email" class="form-control" name="email_contact" id="edit-email-contact">
              </div>
              <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input type="tel" class="form-control" name="telephone" id="edit-telephone">
              </div>
              <div class="col-md-6">
                <label class="form-label">Adresse</label>
                <input type="text" class="form-control" name="adresse" id="edit-adresse">
              </div>
              <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3" id="edit-description-s"></textarea>
              </div>
              <div class="col-md-4">
                <label class="form-label">Ville</label>
                <input type="text" class="form-control" name="ville" id="edit-ville">
              </div>
              <div class="col-md-4">
                <label class="form-label">Durée</label>
                <input type="text" class="form-control" name="duree" id="edit-duree">
              </div>
              <div class="col-md-4">
                <label class="form-label">Niveau d'étude</label>
                <input type="text" class="form-control" name="niveau_etude" id="edit-niveau">
              </div>
              <div class="col-md-4">
                <label class="form-label">Début</label>
                <input type="date" class="form-control" name="date_debut" id="edit-debut">
              </div>
              <div class="col-md-4">
                <label class="form-label">Fin</label>
                <input type="date" class="form-control" name="date_fin" id="edit-fin">
              </div>
              <div class="col-md-4">
                <label class="form-label">Statut</label>
                <select class="form-select" name="statut" id="edit-statut">
                  <option value="disponible">Disponible</option>
                  <option value="rempli">Rempli / Fermé</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary fw-bold">Enregistrer les modifications</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="js/main.js"></script>
<script>
  // --- Fonctions Formulaire Publication ---
  function toggleForm() {
    var type = document.getElementById('type').value;
    document.getElementById('travailForm').classList.toggle('hidden', type !== 'travail');
    document.getElementById('stageForm').classList.toggle('hidden', type !== 'stage');
  }


  // --- Gestion Modale Edition ---
  document.querySelectorAll('.btn-edit-item').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const type = this.dataset.type;
        document.getElementById('edit-id').value = this.dataset.id;
        document.getElementById('edit-type').value = type;

        const travailFields = document.getElementById('editTravailFields');
        const stageFields = document.getElementById('editStageFields');

        travailFields.classList.toggle('hidden', type !== 'travail');
        stageFields.classList.toggle('hidden', type !== 'stage');

        // Activer/Désactiver les inputs pour éviter les collisions de noms lors du POST
        travailFields.querySelectorAll('input, textarea, select').forEach(el => el.disabled = (type !== 'travail'));
        stageFields.querySelectorAll('input, textarea, select').forEach(el => el.disabled = (type !== 'stage'));

        if (type === 'travail') {
            document.getElementById('edit-titre').value = this.dataset.titre;
            document.getElementById('edit-entreprise').value = this.dataset.entreprise;
            document.getElementById('edit-description-t').value = this.dataset.description;
            document.getElementById('edit-localisation').value = this.dataset.localisation;
            document.getElementById('edit-type-contrat').value = this.dataset.type_contrat;
            document.getElementById('edit-experience').value = this.dataset.niveau_experience;
            document.getElementById('edit-domaine').value = this.dataset.domaine;
            document.getElementById('edit-expiration').value = this.dataset.date_expiration;
        } else {
            document.getElementById('edit-nom-societe').value = this.dataset.nom_societe;
            document.getElementById('edit-email-contact').value = this.dataset.email_contact;
            document.getElementById('edit-telephone').value = this.dataset.telephone;
            document.getElementById('edit-adresse').value = this.dataset.adresse;
            document.getElementById('edit-description-s').value = this.dataset.description;
            document.getElementById('edit-ville').value = this.dataset.ville;
            document.getElementById('edit-duree').value = this.dataset.duree;
            document.getElementById('edit-niveau').value = this.dataset.niveau_etude;
            document.getElementById('edit-debut').value = this.dataset.date_debut;
            document.getElementById('edit-fin').value = this.dataset.date_fin;
            document.getElementById('edit-statut').value = this.dataset.statut;
        }
    });
  });

  // --- Suppression ---
  function confirmDelete(id, type) {
      if (confirm("Êtes-vous sûr de vouloir supprimer cette offre ? Cette action est irréversible.")) {
          window.location.href = "index.php?action=delete&id=" + id + "&type=" + type;
      }
  }

  document.addEventListener('DOMContentLoaded', function () {
    toggleForm(); // Initialiser l'état du formulaire

    const params = new URLSearchParams(window.location.search);
    const notifPostule = document.getElementById('notification');
    const notifPubli   = document.getElementById('notif-publication');

    const messages = {
      // Candidatures
      candidature : '✅ Votre candidature a été envoyée avec succès ! 🚀',
      champs_requis     : '❌ Les champs Nom et Email sont obligatoires.',
      fichier_invalide  : '❌ Type de fichier non supporté (PDF, DOC/X uniquement).',
      insertion_echouee : '❌ Une erreur est survenue lors de l\'envoi...',
      
      // Publication d'offres
      travail : '✅ Offre de travail publiée avec succès !',
      stage   : '✅ Stage publié avec succès !',
      'success': 'Félicitations ! Votre action a été effectuée avec succès.',
      'error': 'Une erreur est survenue lors du traitement.',
      // Validation PHP
      'titre_requis': '❌ Le titre de l\'offre est obligatoire.',
      'entreprise_requise': '❌ Le nom de l\'entreprise est obligatoire.',
      'description_requise': '❌ La description est trop courte (min. 10 caractères).',
      'email_invalide': '❌ L\'adresse email saisie est invalide.',
      'tel_invalide': '❌ Le numéro de téléphone doit contenir au moins 8 chiffres.',
      'dates_invalides': '❌ La date de fin doit être postérieure à la date de début.',
      'localisation_requise': '❌ La ville ou localisation est obligatoire.',
      // Existants
      titre_requis      : '❌ Le titre est obligatoire.',
      societe_requise   : '❌ Le nom de la société est obligatoire.',
      type_invalide     : '❌ Type d\'offre invalide.',
      update_echoue     : '❌ Échec de la modification.',
      delete_echoue     : '❌ Échec de la suppression.'
    };

    // Gestion des notifications de publication (SUCCESS/ERROR)
    if (params.has('success')) {
      const key = params.get('success');
      if (key === 'candidature') {
        notifPostule.textContent = messages[key];
        notifPostule.classList.remove('hidden');
        notifPostule.classList.add('alert-success');
      } else {
        notifPubli.textContent = messages[key] || '✅ Opération réussie !';
        notifPubli.classList.remove('hidden');
        notifPubli.classList.add('alert-success');
        document.getElementById('type').value = key; // Ré-afficher le formulaire utilisé
        toggleForm();
      }
    } else if (params.has('error')) {
      const key = params.get('error');
      // On affiche l'erreur là où elle a le plus de sens (par défaut dans notifPubli si c'est une erreur de publication)
      const target = (key === 'champs_requis' || key === 'fichier_invalide') ? notifPostule : notifPubli;
      target.textContent = messages[key] || '❌ Erreur : ' + key;
      target.classList.remove('hidden');
      target.classList.add('alert-error');
    }

    // Toggle Edit Mode
    const toggleBtn = document.getElementById('toggleEditMode');
    toggleBtn.addEventListener('click', function() {
        document.body.classList.toggle('manage-active');
        this.classList.toggle('active');
        if (this.classList.contains('active')) {
            this.innerHTML = '<i class="fas fa-times me-2"></i>Quitter le mode gestion';
        } else {
            this.innerHTML = '<i class="fas fa-edit me-2"></i>Gérer mes publications (Modifier/Supprimer)';
        }
    });

    // Nettoyer l'URL
    if (params.has('success') || params.has('error')) {
      window.history.replaceState({}, document.title, window.location.pathname);
    }
  });
</script>
</body>
</html>
