<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Offres | Career Lab</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon -->
    <link href="Views/assets/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="Views/assets/css/animate.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="Views/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="Views/assets/css/style.css" rel="stylesheet">
    
    <style>
        html, body {
          min-height: 100%;
          background: linear-gradient(180deg, #d3e5ff 0%, #96bbff 45%, #5d8df5 100%);
          background-attachment: fixed;
          color: #0f172a;
        }
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
        .form-card .btn:hover { transform: translateY(-2px); }
        .hidden { display: none !important; }
        
        .offres-hero { padding: 3rem 0 1.5rem; text-align: center; }
        .offres-hero h1 { font-size: 2.6rem; font-weight: 800; color: #fff; text-shadow: 0 2px 12px rgba(0,0,0,.15); }
        .offres-hero p  { color: rgba(255,255,255,.85); font-size: 1.1rem; }
        
        .search-bar { background: rgba(255,255,255,.92); border-radius: 2rem; box-shadow: 0 8px 32px rgba(15,23,42,.1); padding: 1.5rem 2rem; margin-bottom: 2rem; }
        .search-bar .form-control { border-radius: 1rem; border: 1.5px solid rgba(15,23,42,.14); padding: .85rem 1.2rem; }
        .search-bar .btn { border-radius: 1rem; padding: .85rem 1.8rem; font-weight: 700; }
        
        .offer-card {
          background: rgba(255, 255, 255, 0.94);
          border: 1px solid rgba(15, 23, 42, 0.1);
          border-radius: 1.5rem;
          box-shadow: 0 8px 32px rgba(15, 23, 42, 0.07);
          padding: 1.6rem;
          transition: transform .25s, box-shadow .25s;
          height: 100%;
          display: flex;
          flex-direction: column;
        }
        .offer-card:hover { transform: translateY(-5px); box-shadow: 0 20px 50px rgba(15, 23, 42, 0.13); }
        .offer-card .badge-type { font-size: .75rem; font-weight: 700; border-radius: .6rem; padding: .35rem .8rem; margin-bottom: 0.5rem; display: inline-block; }
        .badge-travail { background: #4f46e5; color: #fff; }
        .badge-exp { background: #f59e0b; color: #fff; }
        
        .offer-card h5 { font-size: 1.1rem; font-weight: 700; margin: .7rem 0 .4rem; color: #1e293b; }
        .offer-card .company { font-weight: 600; color: #4f46e5; font-size: .95rem; }
        .offer-card .meta    { font-size: .83rem; color: #64748b; }
        .offer-card .description { font-size: .88rem; color: #475569; flex-grow: 1; margin: .6rem 0; line-height: 1.6; }
        
        .btn-view-item { 
            background: linear-gradient(135deg, #6366f1, #3b82f6); 
            color: #fff; 
            border-radius: .8rem; 
            border: none; 
            padding: .6rem 1rem; 
            font-size: .9rem; 
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }
        .btn-view-item:hover { background: linear-gradient(135deg, #4f46e5, #2563eb); color: #fff; }
        
        .choice-tabs { display: flex; justify-content: center; gap: 1rem; margin-bottom: 2rem; }
        .choice-btn {
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid transparent;
            padding: 0.8rem 2rem;
            border-radius: 1.5rem;
            font-weight: 700;
            color: #4f46e5;
            cursor: pointer;
            transition: all 0.3s;
        }
        .choice-btn.active {
            background: #4f46e5;
            color: #fff;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }
        .section-title-offres { font-size: 1.3rem; font-weight: 800; color: #fff; margin: 2rem 0 1rem; padding-left: .5rem; border-left: 4px solid rgba(255,255,255,0.6); }
        .navbar-dark .navbar-nav .nav-link { color: #fff !important; }
    </style>
</head>
<body>
    <!-- Navbar Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="index.php?page=accueil" class="navbar-brand p-0">
                <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>Career Lab</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.php?page=accueil" class="nav-item nav-link">Home</a>
                    <a href="#" class="nav-item nav-link">À propos</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Services</a>
                        <div class="dropdown-menu m-0">
                            <a href="#" class="dropdown-item">Utilisateurs</a>
                            <a href="#" class="dropdown-item">Métiers</a>
                            <a href="#" class="dropdown-item">Evaluation</a>
                            <a href="index.php?page=offres" class="dropdown-item">Les offres</a>
                            <a href="#" class="dropdown-item">E-learning</a>
                        </div>
                    </div>
                    <a href="#" class="nav-item nav-link">Contact</a>
                </div>
                <button type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i></button>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Form Section -->
    <div class="container-fluid form-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="choice-tabs">
                        <div class="choice-btn active" data-target="form-job" id="btn-choice-job" onclick="document.getElementById('section-offres').scrollIntoView({behavior: 'smooth'})">📢 Opportunité de travail</div>
                        <div class="choice-btn" data-target="form-exp" id="btn-choice-exp" onclick="document.getElementById('section-exp').scrollIntoView({behavior: 'smooth'})">🎓 Expériences</div>
                    </div>

                    <div class="form-card">
                        <div class="mb-4 text-center">
                            <h3 id="form-title">📢 Publier une opportunité de travail</h3>
                            <p class="text-muted">Remplissez les champs pour une offre d'emploi propre et professionnelle.</p>
                        </div>

                        <form id="form-add-travail" action="index.php?page=admin-offre-add" method="POST">
                            <input type="hidden" name="type_offre" value="travail">
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Titre</label><input type="text" class="form-control" name="titre" placeholder="Titre de l'offre" required></div>
                                <div class="col-md-6"><label class="form-label">Entreprise</label><input type="text" class="form-control" name="entreprise" placeholder="Entreprise" required></div>
                                <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3" placeholder="Description de l'offre" required></textarea></div>
                                <div class="col-md-6"><label class="form-label">Localisation</label><input type="text" class="form-control" name="localisation" placeholder="Ville" required></div>
                                <div class="col-md-6"><label class="form-label">Type de contrat</label><input type="text" class="form-control" name="type_contrat" placeholder="CDI, CDD..."></div>
                                <div class="col-md-6">
                                    <label class="form-label">Niveau d'Expérience</label>
                                    <select class="form-select" name="experience_id" required>
                                        <option value="">Sélectionnez...</option>
                                        <?php foreach ($experiences as $exp): ?>
                                            <option value="<?= $exp['id'] ?>"><?= htmlspecialchars($exp['niveau']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6"><label class="form-label">Domaine</label><input type="text" class="form-control" name="domaine" placeholder="Informatique, Gestion..."></div>
                            </div>
                            <div class="mt-4"><button type="submit" class="btn btn-primary w-100">Publier l'Offre</button></div>
                        </form>

                        <form id="form-add-experience" action="index.php?page=admin-offre-add" method="POST" class="hidden">
                            <input type="hidden" name="type_offre" value="experience">
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Nom</label><input type="text" class="form-control" name="nom" required></div>
                                <div class="col-md-6"><label class="form-label">Prénom</label><input type="text" class="form-control" name="prenom" required></div>
                                <div class="col-12">
                                    <label class="form-label">Niveau d'expérience</label>
                                    <select class="form-select" name="niveau" required>
                                        <option value="Junior">Junior</option>
                                        <option value="Intermédiaire">Intermédiaire</option>
                                        <option value="Confirmé">Confirmé</option>
                                        <option value="Senior">Senior</option>
                                        <option value="Expert">Expert</option>
                                    </select>
                                </div>
                                <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="2"></textarea></div>
                            </div>
                            <div class="mt-4"><button type="submit" class="btn btn-primary w-100">Ajouter l'Expérience</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero -->
    <div class="offres-hero">
      <div class="container">
        <h1>🔍 Trouvez votre opportunité</h1>
        <p><?= count($offres) ?> offre<?= count($offres) > 1 ? 's' : '' ?> disponible<?= count($offres) > 1 ? 's' : '' ?></p>
      </div>
    </div>

    <!-- Search & Listing -->
    <div class="container pb-5">
      <form method="GET" action="index.php" class="search-bar">
        <input type="hidden" name="page" value="offres">
        <div class="row g-2 align-items-center">
          <div class="col-md-7"><input type="text" name="q" class="form-control" placeholder="🔎 Rechercher..." value="<?= htmlspecialchars($search ?? '') ?>"></div>
          <div class="col-md-3">
            <select name="sort" class="form-select" onchange="this.form.submit()">
              <option value="newest" <?= ($sort ?? '') === 'newest' ? 'selected' : '' ?>>📅 Plus récent</option>
              <option value="title_asc" <?= ($sort ?? '') === 'title_asc' ? 'selected' : '' ?>>🔤 Titre (A-Z)</option>
            </select>
          </div>
          <div class="col-md-2"><button type="submit" class="btn btn-info text-white w-100" style="background: #0ea5e9; border: none;">Rechercher</button></div>
        </div>
      </form>

      <div class="text-center mb-4">
          <button onclick="window.location.href='index.php?page=admin-offres'" class="btn btn-warning text-white px-4 py-2 rounded-pill fw-bold shadow-sm" style="background: #f59e0b; border: none;">
              <i class="fas fa-edit me-2"></i> Gérer mes publications
          </button>
      </div>

      <div class="section-title-offres" id="section-offres">💼 Opportunités d'Emploi</div>
      <div class="row g-4 mb-5">
        <?php foreach ($offres as $t): ?>
        <div class="col-md-6 col-lg-4">
          <div class="offer-card">
            <span class="badge-type badge-travail">Emploi</span>
            <h5><?= htmlspecialchars($t['titre']) ?></h5>
            <div class="company"><i class="fas fa-building me-1"></i><?= htmlspecialchars($t['entreprise']) ?></div>
            <div class="meta"><?= htmlspecialchars($t['localisation']) ?> | <?= htmlspecialchars($t['domaine']) ?></div>
            <div class="description"><?= htmlspecialchars(substr($t['description'], 0, 100)) ?>...</div>
            <a href="index.php?page=offre-details&id=<?= $t['id'] ?>" class="btn-view-item">
              <i class="fas fa-eye"></i> Afficher
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="section-title-offres" id="section-exp">🎓 Niveaux d'Expérience</div>
      <div class="row g-4">
        <?php if (!empty($experiences)): ?>
            <?php foreach ($experiences as $exp): ?>
            <div class="col-md-6 col-lg-4">
              <div class="offer-card">
                <span class="badge-type badge-exp">Expérience</span>
                <h5><?= htmlspecialchars($exp['niveau']) ?></h5>
                <div class="description"><?= htmlspecialchars($exp['description'] ?? 'Aucune description.') ?></div>
                <a href="#" class="btn-view-item">
                  <i class="fas fa-eye"></i> Afficher
                </a>
              </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-white"><p>Aucun niveau d'expérience disponible.</p></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-4 col-md-6 footer-about">
                    <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 bg-primary p-4">
                        <a href="#" class="navbar-brand">
                            <h1 class="m-0 text-white"><i class="fa fa-user-tie me-2"></i>Career Lab</h1>
                        </a>
                        <p class="mt-3 mb-4">La plateforme leader pour trouver votre stage ou votre premier emploi. Nous connectons les talents aux meilleures entreprises.</p>
                        <div class="input-group">
                            <input type="text" class="form-control border-white p-3" placeholder="Votre Email">
                            <button class="btn btn-dark">S'inscrire</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <div class="row gx-5">
                        <div class="col-lg-4 col-md-12 pt-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Contact</h3>
                            </div>
                            <div class="d-flex mb-2"><i class="bi bi-geo-alt text-primary me-2"></i><p class="mb-0">123 Street, Tunis, Tunisie</p></div>
                            <div class="d-flex mb-2"><i class="bi bi-envelope-open text-primary me-2"></i><p class="mb-0">info@careerlab.tn</p></div>
                            <div class="d-flex mb-2"><i class="bi bi-telephone text-primary me-2"></i><p class="mb-0">+216 71 000 000</p></div>
                        </div>
                        <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Liens Rapides</h3>
                            </div>
                            <div class="link-animated d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>À propos</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Offres</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Services</h3>
                            </div>
                            <div class="link-animated d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Métiers</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Evaluation</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid text-white" style="background: #061429;">
        <div class="container text-center">
            <div class="row justify-content-end">
                <div class="col-lg-8 col-md-6">
                    <div class="d-flex align-items-center justify-content-center" style="height: 75px;">
                        <p class="mb-0">&copy; <a class="text-white border-bottom" href="#">Career Lab</a>. Tous droits réservés.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const btnJob = document.getElementById('btn-choice-job');
        const btnExp = document.getElementById('btn-choice-exp');
        const formJob = document.getElementById('form-add-travail');
        const formExp = document.getElementById('form-add-experience');
        const formTitle = document.getElementById('form-title');

        btnJob.addEventListener('click', () => {
            btnJob.classList.add('active');
            btnExp.classList.remove('active');
            formJob.classList.remove('hidden');
            formExp.classList.add('hidden');
            formTitle.innerText = '📢 Publier une opportunité de travail';
        });

        btnExp.addEventListener('click', () => {
            btnExp.classList.add('active');
            btnJob.classList.remove('active');
            formExp.classList.remove('hidden');
            formJob.classList.add('hidden');
            formTitle.innerText = '🎓 Ajouter une expérience';
        });
    </script>
</body>
</html>
