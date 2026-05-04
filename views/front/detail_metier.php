<?php
// Démarrer la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier que $metier_detail existe (passé depuis index.php)
if (!isset($metier_detail) || !$metier_detail) {
    $_SESSION['errors'] = ["Métier non trouvé"];
    header('Location: index.php?action=metiers');
    exit;
}

$title = "Détail du métier";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Career Lab - <?= htmlspecialchars($metier_detail['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: #f8f9fa;
        }
        
        .detail-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-top: 30px;
        }
        
        .detail-header {
            background: #1a5f7a;
            color: white;
            padding: 25px 30px;
        }
        
        .detail-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .detail-body {
            padding: 30px;
            background: white;
        }
        
        .stat-badge {
            background: rgba(255,255,255,0.2);
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a5f7a;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .section-title i {
            margin-right: 10px;
            color: #1a5f7a;
        }
        
        .section-content {
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        
        .salary-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #28a745;
        }
        
        .btn-back {
            background: #6c757d;
            color: white;
            border-radius: 30px;
            padding: 10px 30px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background: #5a6268;
            color: white;
        }
        
        .btn-postuler {
            background: #28a745;
            color: white;
            border-radius: 30px;
            padding: 12px 35px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-postuler:hover {
            background: #1e7e34;
            color: white;
        }
        
        .logo-img {
            height: 40px;
        }
        
        .navbar-brand {
            padding: 0;
        }
        
        .btn-primary {
            background-color: #1a5f7a;
            border-color: #1a5f7a;
        }
        
        .btn-primary:hover {
            background-color: #0e3d52;
            border-color: #0e3d52;
        }
        
        .share-section {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
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

<!-- Search Modal -->
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

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="detail-card">
                <div class="detail-header">
                    <h1><i class="fas fa-briefcase me-2"></i><?= htmlspecialchars($metier_detail['title']) ?></h1>
                    <div class="d-flex gap-3 flex-wrap mt-3">
                        <span class="stat-badge">
                            <i class="fas fa-eye"></i> <?= $metier_detail['views'] ?> vues
                        </span>
                        <span class="stat-badge like-btn <?= (isset($_SESSION['liked_' . $metier_detail['id']]) && $_SESSION['liked_' . $metier_detail['id']]) ? 'liked' : '' ?>" 
                              onclick="toggleLike(<?= $metier_detail['id'] ?>, this)" style="cursor: pointer;">
                            <i class="fas fa-heart"></i>
                            <span class="like-count"><?= $metier_detail['likes'] ?? 0 ?></span> likes
                        </span>
                        <span class="stat-badge" style="cursor: pointer;" onclick="showComments(<?= $metier_detail['id'] ?>)" data-bs-toggle="modal" data-bs-target="#commentsModal">
                            <i class="fas fa-comment"></i>
                            <span class="comment-count"><?= $metier_detail['comment_count'] ?? 0 ?></span> commentaires
                        </span>
                    </div>
                </div>
                <div class="detail-body">
                    <!-- Catégorie -->
                    <div>
                        <div class="section-title">
                            <i class="fas fa-tag"></i> Catégorie
                        </div>
                        <div class="section-content">
                            <span class="badge bg-secondary"><?= htmlspecialchars($metier_detail['category_name'] ?? 'Non catégorisé') ?></span>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <div class="section-title">
                            <i class="fas fa-align-left"></i> Description
                        </div>
                        <div class="section-content">
                            <?= nl2br(htmlspecialchars($metier_detail['description'] ?? 'Aucune description disponible.')) ?>
                        </div>
                    </div>
                    
                    <!-- Compétences et Spécialités -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="section-title">
                                <i class="fas fa-code"></i> Compétences requises
                            </div>
                            <div class="section-content">
                                <?= nl2br(htmlspecialchars($metier_detail['competences'] ?? 'Non spécifiées.')) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="section-title">
                                <i class="fas fa-star"></i> Spécialités
                            </div>
                            <div class="section-content">
                                <?= nl2br(htmlspecialchars($metier_detail['specialites'] ?? 'Non spécifiées.')) ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Salaire -->
                    <div>
                        <div class="section-title">
                            <i class="fas fa-euro-sign"></i> Salaire annuel
                        </div>
                        <div class="section-content">
                            <span class="salary-value">
                                <?= $metier_detail['salaire'] ? number_format($metier_detail['salaire'], 0, ',', ' ') . ' €' : 'Non spécifié' ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Boutons Postuler et Retour -->
                    <div class="text-center mt-4 d-flex gap-3 justify-content-center">
                        <a href="index.php?action=postuler&id=<?= $metier_detail['id'] ?>" class="btn-postuler">
                            <i class="fas fa-paper-plane me-2"></i> Postuler maintenant
                        </a>
                        <a href="index.php?action=metiers" class="btn-back">
                            <i class="fas fa-arrow-left me-2"></i> Retour
                        </a>
                    </div>
                    
                    <!-- Boutons de partage social -->
                    <div class="share-section">
                        <div class="section-title" style="font-size: 1rem;">
                            <i class="fas fa-share-alt"></i> Partager cette offre
                        </div>
                        <div class="d-flex gap-2 mt-3 flex-wrap justify-content-center">
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" 
                               target="_blank" class="btn btn-primary" style="background:#1877f2; border:none;">
                                <i class="fab fa-facebook-f me-1"></i> Facebook
                            </a>
                            
                            <!-- Twitter -->
                            <a href="https://twitter.com/intent/tweet?text=<?= urlencode('Je découvre le métier de ' . $metier_detail['title'] . ' sur Career Lab !') ?>&url=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" 
                               target="_blank" class="btn btn-primary" style="background:#1da1f2; border:none;">
                                <i class="fab fa-twitter me-1"></i> Twitter
                            </a>
                            
                            <!-- LinkedIn -->
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" 
                               target="_blank" class="btn btn-primary" style="background:#0077b5; border:none;">
                                <i class="fab fa-linkedin-in me-1"></i> LinkedIn
                            </a>
                            
                            <!-- WhatsApp -->
                            <a href="https://api.whatsapp.com/send?text=<?= urlencode('Découvrez ce poste de ' . $metier_detail['title'] . ' sur Career Lab ! http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" 
                               target="_blank" class="btn btn-primary" style="background:#25d366; border:none;">
                                <i class="fab fa-whatsapp me-1"></i> WhatsApp
                            </a>
                            
                            <!-- Copier lien -->
                            <button onclick="copyLink()" class="btn btn-secondary" style="background:#6c757d; border:none;">
                                <i class="fas fa-copy me-1"></i> Copier le lien
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer Start -->
<div class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="row gx-5">
            <div class="col-lg-4 col-md-6 footer-about">
                <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 bg-primary p-4" style="background-color:#1a5f7a !important;">
                    <a href="index.php" class="navbar-brand">
                        <img src="assets/img/logo.png" alt="Career Lab" style="height: 40px;">
                    </a>
                    <p class="mt-3 mb-4">Votre plateforme de découverte des métiers et opportunités professionnelles.</p>
                </div>
            </div>
            <div class="col-lg-8 col-md-6">
                <div class="row gx-5">
                    <div class="col-lg-4 col-md-12 pt-5 mb-5">
                        <div class="section-title section-title-sm position-relative pb-3 mb-4">
                            <h3 class="text-light mb-0">Get In Touch</h3>
                        </div>
                        <div class="d-flex mb-2">
                            <i class="bi bi-geo-alt text-primary me-2" style="color:#1a5f7a !important;"></i>
                            <p class="mb-0">123 Street, New York, USA</p>
                        </div>
                        <div class="d-flex mb-2">
                            <i class="bi bi-envelope-open text-primary me-2" style="color:#1a5f7a !important;"></i>
                            <p class="mb-0">info@careerlab.com</p>
                        </div>
                        <div class="d-flex mb-2">
                            <i class="bi bi-telephone text-primary me-2" style="color:#1a5f7a !important;"></i>
                            <p class="mb-0">+012 345 67890</p>
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
                    <p class="mb-0">&copy; <a class="text-white border-bottom" href="#">Career Lab</a>. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- Modal Commentaires -->
<div class="modal fade" id="commentsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: #1a5f7a; color: white;">
                <h5 class="modal-title"><i class="fas fa-comments me-2"></i>Commentaires</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="currentMetierId" value="<?= $metier_detail['id'] ?>">
                
                <div id="commentsList" style="max-height: 300px; overflow-y: auto;">
                    <p class="text-muted text-center">Chargement...</p>
                </div>
                
                <hr>
                <h6><i class="fas fa-pen me-2"></i>Ajouter un commentaire</h6>
                <div class="mb-2">
                    <input type="text" id="commentAuthor" class="form-control form-control-sm" placeholder="Votre nom (optionnel)">
                </div>
                <div class="mb-2">
                    <textarea id="commentContent" class="form-control" rows="2" placeholder="Votre commentaire..."></textarea>
                </div>
                <button onclick="addComment()" class="btn btn-primary btn-sm w-100" style="background:#1a5f7a; border:none;">
                    <i class="fas fa-paper-plane me-2"></i>Publier
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleLike(metierId, element) {
    fetch('index.php?action=toggle_like&id=' + metierId, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const countSpan = element.querySelector('.like-count');
            if (countSpan) countSpan.textContent = data.likes;
            if (data.liked) {
                element.classList.add('liked');
            } else {
                element.classList.remove('liked');
            }
        }
    })
    .catch(error => console.error('Erreur:', error));
}

function showComments(metierId) {
    document.getElementById('currentMetierId').value = metierId;
    loadComments(metierId);
}

function loadComments(metierId) {
    fetch('index.php?action=get_comments&id=' + metierId)
    .then(response => response.json())
    .then(data => {
        const commentsList = document.getElementById('commentsList');
        commentsList.innerHTML = '';
        if (data.comments.length === 0) {
            commentsList.innerHTML = '<p class="text-muted text-center">Aucun commentaire pour le moment.</p>';
        } else {
            data.comments.forEach(comment => {
                commentsList.innerHTML += `
                    <div class="comment-item">
                        <strong>${escapeHtml(comment.author)}</strong>
                        <span class="text-muted ms-2" style="font-size: 0.7rem;">${comment.date}</span>
                        <p class="mb-0 mt-1">${escapeHtml(comment.content)}</p>
                    </div>
                    <hr>
                `;
            });
        }
    });
}

function addComment() {
    const metierId = document.getElementById('currentMetierId').value;
    const author = document.getElementById('commentAuthor').value || 'Anonyme';
    const content = document.getElementById('commentContent').value;
    
    if (!content.trim()) {
        alert('Veuillez écrire un commentaire');
        return;
    }
    
    fetch('index.php?action=add_comment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'metier_id=' + metierId + '&author=' + encodeURIComponent(author) + '&content=' + encodeURIComponent(content)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('commentContent').value = '';
            loadComments(metierId);
            const commentSpan = document.querySelector('.comment-count');
            if (commentSpan) commentSpan.textContent = data.total;
        }
    });
}

function copyLink() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        alert('Lien copié dans le presse-papier !');
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
</body>
</html>