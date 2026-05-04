<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($offre) || !$offre) {
    header('Location: index.php?action=metiers');
    exit;
}

$title = "Postuler - " . htmlspecialchars($offre['title']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Career Lab - Postuler</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Nunito', sans-serif; background: #f8f9fa; }
        .form-card { border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .form-header { background: #1a5f7a; color: white; padding: 20px; }
        .form-body { padding: 30px; background: white; }
        .btn-submit { background: #1a5f7a; border: none; padding: 12px 30px; border-radius: 30px; color: white; font-weight: 600; }
        .btn-submit:hover { background: #0e3d52; }
        .logo-img { height: 40px; }
        .navbar-brand { padding: 0; }
        .btn-primary {
            background-color: #1a5f7a;
            border-color: #1a5f7a;
        }
        .btn-primary:hover {
            background-color: #0e3d52;
            border-color: #0e3d52;
        }
    </style>
</head>
<body>

<!-- Topbar Start - BLEU MARINE (comme metiers.php) -->
<div class="container-fluid px-5 d-none d-lg-block" style="background-color: #1a5f7a;">
    <div class="row gx-0">
        <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
            <div class="d-inline-flex align-items-center" style="height: 45px;">
                <small class="me-3 text-white"><i class="fa fa-map-marker-alt me-2"></i>123 Street, New York, USA</small>
                <small class="me-3 text-white"><i class="fa fa-phone-alt me-2"></i>+012 345 6789</small>
                <small class="text-white"><i class="fa fa-envelope-open me-2"></i>info@example.com</small>
            </div>
        </div>
        <div class="col-lg-4 text-center text-lg-end">
            <div class="d-inline-flex align-items-center" style="height: 45px;">
                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#"><i class="fab fa-twitter fw-normal text-white"></i></a>
                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#"><i class="fab fa-facebook-f fw-normal text-white"></i></a>
                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#"><i class="fab fa-linkedin-in fw-normal text-white"></i></a>
                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#"><i class="fab fa-instagram fw-normal text-white"></i></a>
                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle" href="#"><i class="fab fa-youtube fw-normal text-white"></i></a>
            </div>
        </div>
    </div>
</div>
<!-- Topbar End -->

<!-- Navbar Start (identique à metiers.php) -->
<div class="container-fluid position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
        <a href="index.php" class="navbar-brand p-0">
            <img src="assets/img/logo.png" alt="Career Lab" class="logo-img" style="height: 40px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="index.php" class="nav-item nav-link">Accueil</a>
                <a href="#" class="nav-item nav-link">About</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">services</a>
                    <div class="dropdown-menu m-0">
                        <a href="index.php?action=metiers" class="dropdown-item active">métiers</a>
                    </div>
                </div>
                <a href="#" class="nav-item nav-link">Contact</a>
            </div>
            <button type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal">
                <i class="fa fa-search"></i>
            </button>
            <a href="index.php?action=admin_metiers" class="btn btn-primary py-2 px-4 ms-3">Administration</a>
        </div>
    </nav>
</div>
<!-- Navbar End -->

<!-- Search Modal (identique à metiers.php) -->
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="background: rgba(9, 30, 62, .7);">
            <div class="modal-header border-0">
                <button type="button" class="btn bg-white btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center">
                <div class="input-group" style="max-width: 600px;">
                    <form method="GET" action="index.php" class="d-flex w-100">
                        <input type="hidden" name="action" value="search">
                        <input type="text" name="search" class="form-control bg-transparent border-primary p-3" placeholder="Rechercher un métier...">
                        <button class="btn btn-primary px-4" type="submit"><i class="bi bi-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-card">
                <div class="form-header">
                    <h3><i class="fas fa-paper-plane me-2"></i>Postuler : <?= htmlspecialchars($offre['title']) ?></h3>
                </div>
                <div class="form-body">
                    <form method="POST" action="index.php?action=envoyer_candidature" enctype="multipart/form-data">
                        <input type="hidden" name="metier_id" value="<?= $offre['id'] ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nom complet *</label>
                                <input type="text" name="nom" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Téléphone</label>
                            <input type="tel" name="telephone" class="form-control">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">CV (PDF, DOC) *</label>
                            <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Message (optionnel)</label>
                            <textarea name="message" class="form-control" rows="4" placeholder="Pourquoi postulez-vous ?"></textarea>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-envelope me-2"></i>Envoyer ma candidature
                            </button>
                            <a href="index.php?action=detail_metier&id=<?= $offre['id'] ?>" class="btn btn-secondary ms-2">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>