<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($offre['titre']) ?> | Career Lab</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="Views/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="Views/assets/css/style.css" rel="stylesheet">
    
    <style>
        html, body {
          min-height: 100%;
          background: linear-gradient(180deg, #d3e5ff 0%, #96bbff 45%, #5d8df5 100%);
          background-attachment: fixed;
          color: #0f172a;
        }
        .detail-container { padding: 4rem 0; }
        .detail-card {
          background: rgba(255, 255, 255, 0.95);
          border-radius: 2rem;
          box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
          overflow: hidden;
          border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .detail-header {
          background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
          color: white;
          padding: 3rem 2rem;
        }
        .badge-type {
          display: inline-block;
          padding: 0.5rem 1.25rem;
          border-radius: 9999px;
          font-weight: 700;
          text-transform: uppercase;
          font-size: 0.75rem;
          background: rgba(255, 255, 255, 0.2); 
          border: 1px solid rgba(255, 255, 255, 0.4);
          margin-bottom: 1.5rem;
        }
        .detail-title { font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; }
        .detail-body { padding: 3rem 2.5rem; }
        .btn-back {
          display: inline-flex;
          align-items: center;
          gap: 0.5rem;
          padding: 0.75rem 1.5rem;
          background: white;
          color: #4f46e5;
          border-radius: 1rem;
          font-weight: 700;
          text-decoration: none;
          margin-bottom: 2rem;
          box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
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
            <div class="navbar-nav ms-auto py-0">
                <a href="index.php?page=offres" class="nav-item nav-link">Retour aux offres</a>
            </div>
        </nav>
    </div>

    <div class="container detail-container">
        <a href="index.php?page=offres" class="btn-back">
            <i class="fas fa-arrow-left"></i> Retour aux offres
        </a>

        <div class="detail-card">
            <div class="detail-header">
                <span class="badge-type">Offre d'Emploi</span>
                <h1 class="detail-title"><?= htmlspecialchars($offre['titre']) ?></h1>
                <div class="fs-5 opacity-90"><i class="fas fa-building me-2"></i><?= htmlspecialchars($offre['entreprise']) ?></div>
            </div>

            <div class="detail-body">
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <div class="text-muted small fw-bold uppercase">Localisation</div>
                            <div class="fw-bold"><?= htmlspecialchars($offre['localisation']) ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <div class="text-muted small fw-bold uppercase">Contrat</div>
                            <div class="fw-bold"><?= htmlspecialchars($offre['type_contrat'] ?? 'CDI') ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <div class="text-muted small fw-bold uppercase">Domaine</div>
                            <div class="fw-bold"><?= htmlspecialchars($offre['domaine']) ?></div>
                        </div>
                    </div>
                </div>

                <h4 class="fw-bold mb-3">Description du poste</h4>
                <div class="fs-5 text-muted mb-5" style="white-space: pre-line;">
                    <?= nl2br(htmlspecialchars($offre['description'])) ?>
                </div>

                <div class="text-center">
                    <button class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-lg fw-bold" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); border: none;" data-bs-toggle="modal" data-bs-target="#applyModal">
                        Postuler maintenant
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Postuler -->
    <div class="modal fade" id="applyModal" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
          <div class="modal-header text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); border-bottom: none; padding: 1.5rem 2rem;">
            <h5 class="modal-title fw-bold"><i class="fas fa-rocket me-2"></i> Candidature IA - <?= htmlspecialchars($offre['titre']) ?></h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <form action="index.php?page=postuler" method="POST">
              <input type="hidden" name="offre_id" value="<?= $offre['id'] ?>">
              <div class="modal-body p-4 p-md-5 bg-light">
                  <div class="row g-4">
                      <div class="col-md-6">
                          <label class="form-label fw-bold">Nom complet</label>
                          <input type="text" name="nom_candidat" class="form-control" required>
                      </div>
                      <div class="col-md-6">
                          <label class="form-label fw-bold">Email</label>
                          <input type="email" name="email_candidat" class="form-control" required>
                      </div>
                      <div class="col-12">
                          <label class="form-label fw-bold">CV (Texte brut)</label>
                          <textarea name="cv_texte" class="form-control" rows="6" required placeholder="Copiez votre CV ici..."></textarea>
                      </div>
                  </div>
              </div>
              <div class="modal-footer bg-white border-0 p-4">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); border: none;">Envoyer ma candidature</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light mt-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <h1 class="text-white mb-4"><i class="fa fa-user-tie me-2"></i>Career Lab</h1>
                    <p>La plateforme leader pour trouver votre stage ou votre premier emploi.</p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h3 class="text-light mb-4">Contact</h3>
                    <p><i class="fa fa-map-marker-alt me-2"></i>123 Street, Tunis, Tunisie</p>
                    <p><i class="fa fa-phone-alt me-2"></i>+216 71 000 000</p>
                    <p><i class="fa fa-envelope me-2"></i>info@careerlab.tn</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid text-white py-4" style="background: #061429;">
        <div class="container text-center">
            <p class="mb-0">&copy; <a class="text-white border-bottom" href="#">Career Lab</a>. Tous droits réservés.</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
