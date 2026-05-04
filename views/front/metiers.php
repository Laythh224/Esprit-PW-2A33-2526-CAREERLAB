<?php 
$title = "Métiers"; 
$action = "metiers"; 
$metiers = $metiers ?? [];
$categories = $categories ?? [];

// Récupérer les paramètres de filtre et tri
$search = $_GET['search'] ?? '';
$category_filter = $_GET['category'] ?? '';
$sort_by = $_GET['sort'] ?? 'id';
$sort_order = $_GET['order'] ?? 'DESC';
$page = (int)($_GET['page'] ?? 1);
$per_page = 6;

// Filtrer par catégorie
if (!empty($category_filter)) {
    $metiers = array_filter($metiers, function($m) use ($category_filter) {
        return $m->getCategoryId() == $category_filter;
    });
}

// Trier les métiers
usort($metiers, function($a, $b) use ($sort_by, $sort_order) {
    $val_a = '';
    $val_b = '';
    switch($sort_by) {
        case 'title': $val_a = $a->getTitle(); $val_b = $b->getTitle(); break;
        case 'salaire': $val_a = $a->getSalaire(); $val_b = $b->getSalaire(); break;
        case 'id': default: $val_a = $a->getId(); $val_b = $b->getId(); break;
    }
    if ($sort_order == 'ASC') {
        return $val_a <=> $val_b;
    } else {
        return $val_b <=> $val_a;
    }
});

// Pagination
$total = count($metiers);
$total_pages = ceil($total / $per_page);
$offset = ($page - 1) * $per_page;
$metiers_paginated = array_slice($metiers, $offset, $per_page);

// ========== INCRÉMENTER LES VUES ==========
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $db = new PDO('mysql:host=localhost;dbname=mon_site;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    foreach ($metiers_paginated as $metier) {
        $id = $metier->getId();
        if (!isset($_SESSION['vu_metier_front_' . $id])) {
            $sql = "UPDATE job SET views = views + 1 WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $_SESSION['vu_metier_front_' . $id] = true;
        }
    }
} catch (PDOException $e) {
    // Silencieux
}
// ========== FIN INCRÉMENTATION ==========

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Career Lab - Métiers</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="assets/img/logo.png" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .filter-card { background: #f8f9fa; border-radius: 15px; padding: 20px; margin-bottom: 30px; }
        .sort-link { color: #2c3e6d; text-decoration: none; margin: 0 5px; }
        .sort-link:hover { text-decoration: underline; }
        .sort-active { font-weight: bold; color: #1a5f7a; }
        .pagination .page-link { color: #2c3e6d; }
        .pagination .active .page-link { background: #1a5f7a; border-color: #1a5f7a; color: white; }
        .result-count { font-size: 0.9rem; color: #6c757d; margin-bottom: 15px; }
        .logo-img { height: 40px; }
        .navbar-brand { padding: 0; }
        .card-header-metier { background: #1a5f7a; color: white; padding: 12px 15px; border-radius: 10px 10px 0 0; }
        .btn-primary { background-color: #1a5f7a; border-color: #1a5f7a; }
        .btn-primary:hover { background-color: #0e3d52; border-color: #0e3d52; }
        .badge-instagram { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 30px; font-size: 0.7rem; font-weight: 600; }
        .badge-new { background: #2c7da0; color: white; }
        .badge-verified { background: #2e7d64; color: white; }
        .badge-verified i { color: #ffd700; }
        .badge-popular { background: #d4a373; color: white; }
        .badge-trending { background: #e9c46a; color: #2c3e6d; }
        .metier-badge-container { display: flex; gap: 6px; flex-wrap: wrap; padding: 8px 15px 0 15px; }
        .metier-stats { display: flex; align-items: center; gap: 10px; margin-top: 10px; }
        .metier-stat { display: inline-flex; align-items: center; gap: 5px; background: rgba(255,255,255,0.15); padding: 4px 10px; border-radius: 30px; font-size: 0.7rem; cursor: pointer; }
        .metier-stat.like.liked i { color: #ff4757; }
        .views-bar { width: 100%; height: 4px; background: rgba(255,255,255,0.2); border-radius: 10px; margin-top: 8px; overflow: hidden; }
        .views-bar-fill { height: 100%; background: linear-gradient(90deg, #ffd700, #ff8c00); border-radius: 10px; transition: width 0.3s ease; }
        .view-count { display: inline-flex; align-items: center; gap: 4px; background: rgba(255,255,255,0.15); padding: 4px 10px; border-radius: 30px; font-size: 0.7rem; }
    </style>
</head>
<body>

<!-- Topbar Start - BLEU MARINE -->
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

<!-- Navbar Start -->
<div class="container-fluid position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
        <a href="index.php" class="navbar-brand p-0">
            <img src="assets/img/logo.png" alt="Career Lab" class="logo-img">
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
            <button type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i></button>
            <a href="index.php?action=admin_metiers" class="btn btn-primary py-2 px-4 ms-3">Administration</a>
        </div>
    </nav>
</div>
<!-- Navbar End -->

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="background: rgba(9, 30, 62, .7);">
            <div class="modal-header border-0">
                <button type="button" class="btn bg-white btn-close" data-bs-dismiss="modal"></button>
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

<!-- Messages flash -->
<div class="container mt-4">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $_SESSION['success'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= implode('<br>', $_SESSION['errors']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>
</div>

<!-- SECTION MÉTIERS -->
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="text-center mb-4">
                <h1 class="display-5 fw-bold">Entrez votre métier</h1>
                <p class="lead">Construisez votre avenir professionnel dès aujourd'hui</p>
            </div>
            
            <!-- Barre de recherche -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-6">
                    <form method="GET" action="index.php" class="input-group">
                        <input type="hidden" name="action" value="search">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher un métier..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Rechercher</button>
                    </form>
                </div>
            </div>
            
            <!-- Filtres et Tri -->
            <div class="filter-card">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <label class="fw-bold me-2">Filtrer par catégorie :</label>
                        <select class="form-select form-select-sm d-inline-block w-auto" onchange="window.location.href=this.value">
                            <option value="index.php?action=metiers&search=<?= urlencode($search) ?>&sort=<?= $sort_by ?>&order=<?= $sort_order ?>">Toutes les catégories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="index.php?action=metiers&category=<?= $cat->getId() ?>&search=<?= urlencode($search) ?>&sort=<?= $sort_by ?>&order=<?= $sort_order ?>" <?= ($category_filter == $cat->getId()) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat->getName()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-8 text-md-end">
                        <label class="fw-bold me-2">Trier par :</label>
                        <a href="index.php?action=metiers&search=<?= urlencode($search) ?>&category=<?= $category_filter ?>&sort=id&order=<?= ($sort_by == 'id' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>" class="sort-link <?= ($sort_by == 'id') ? 'sort-active' : '' ?>">Date <?= ($sort_by == 'id' && $sort_order == 'ASC') ? '↑' : (($sort_by == 'id') ? '↓' : '') ?></a>
                        <a href="index.php?action=metiers&search=<?= urlencode($search) ?>&category=<?= $category_filter ?>&sort=title&order=<?= ($sort_by == 'title' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>" class="sort-link <?= ($sort_by == 'title') ? 'sort-active' : '' ?>">Titre <?= ($sort_by == 'title' && $sort_order == 'ASC') ? '↑' : (($sort_by == 'title') ? '↓' : '') ?></a>
                        <a href="index.php?action=metiers&search=<?= urlencode($search) ?>&category=<?= $category_filter ?>&sort=salaire&order=<?= ($sort_by == 'salaire' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>" class="sort-link <?= ($sort_by == 'salaire') ? 'sort-active' : '' ?>">Salaire <?= ($sort_by == 'salaire' && $sort_order == 'ASC') ? '↑' : (($sort_by == 'salaire') ? '↓' : '') ?></a>
                    </div>
                </div>
            </div>
            
            <!-- Nombre de résultats -->
            <div class="result-count">
                <i class="fas fa-chart-line me-1"></i> <?= $total ?> métier(s) trouvé(s)
                <?php if (!empty($search)): ?> pour "<strong><?= htmlspecialchars($search) ?></strong>"<?php endif; ?>
            </div>
            
            <!-- Formulaire AJOUTER -->
            <div class="card mb-5 border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Proposer un nouveau métier</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?action=add_metier_public">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="title" class="form-control" placeholder="Titre du métier *">
                            </div>
                            <div class="col-md-6 mb-3">
                                <select name="category_id" class="form-select">
                                    <option value="">-- Choisir une catégorie --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat->getId() ?>"><?= htmlspecialchars($cat->getName()) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <textarea name="description" class="form-control" rows="3" placeholder="Description du métier"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <textarea name="competences" class="form-control" rows="3" placeholder="Compétences requises"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <textarea name="specialites" class="form-control" rows="3" placeholder="Spécialités"></textarea>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="number" name="salaire" class="form-control" placeholder="Salaire annuel (€)" step="1000">
                        </div>
                        <button type="submit" class="btn btn-success w-100"> Proposer</button>
                    </form>
                </div>
            </div>
            
         <!-- Liste des métiers -->
<h3 class="mb-3"><i class="fas fa-list"></i> Métiers existants</h3>
<div class="row">
    <?php if (empty($metiers_paginated)): ?>
        <div class="alert alert-warning text-center">Aucun métier trouvé.</div>
    <?php else: ?>
        <?php foreach ($metiers_paginated as $metier): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header-metier" style="position: relative; background: linear-gradient(135deg, #1a5f7a 0%, #0e3d52 100%); color: white;">
                    <h5 class="mb-0">
                        <a href="index.php?action=detail_metier&id=<?= $metier->getId() ?>" style="color: white; text-decoration: none;">
                            <i class="fas fa-briefcase me-2"></i><?= htmlspecialchars($metier->getTitle()) ?>
                        </a>
                    </h5>
                    
                    <!-- BADGE DYNAMIQUE -->
                    <?php 
                    $views = $metier->getViews() ?? 0;
                    if ($views > 20): ?>
                        <span class="badge" style="position: absolute; top: 10px; right: 15px; background: #ff4757; color: white; font-size: 0.7rem; padding: 5px 12px; border-radius: 20px;">
                            🔥 TRENDING (<?= $views ?> vues)
                        </span>
                    <?php elseif ($views > 10): ?>
                        <span class="badge" style="position: absolute; top: 10px; right: 15px; background: #ffa502; color: #2c3e6d; font-size: 0.7rem; padding: 5px 12px; border-radius: 20px;">
                            ⭐ POPULAIRE (<?= $views ?> vues)
                        </span>
                    <?php elseif ($views > 0): ?>
                        <span class="badge" style="position: absolute; top: 10px; right: 15px; background: #1e90ff; color: white; font-size: 0.7rem; padding: 5px 12px; border-radius: 20px;">
                            👁️ <?= $views ?> vues
                        </span>
                    <?php else: ?>
                        <span class="badge" style="position: absolute; top: 10px; right: 15px; background: #2ed573; color: white; font-size: 0.7rem; padding: 5px 12px; border-radius: 20px;">
                            🆕 NOUVEAU
                        </span>
                    <?php endif; ?>
                    
                    <!-- STATS -->
                    <div class="metier-stats mt-2" style="display: flex; gap: 15px; font-size: 0.75rem;">
                        <span class="view-count">
                            <i class="fas fa-eye"></i> <strong><?= $views ?></strong> vues
                        </span>
                        <span class="metier-stat like" onclick="toggleLike(<?= $metier->getId() ?>, this)" style="cursor: pointer;">
                            <i class="fas fa-heart"></i> <span class="like-count-<?= $metier->getId() ?>"><?= $metier->getLikes() ?? 0 ?></span> likes
                        </span>
                        <span class="metier-stat comment" onclick="showComments(<?= $metier->getId() ?>)" data-bs-toggle="modal" data-bs-target="#commentsModal" style="cursor: pointer;">
                            <i class="fas fa-comment"></i> <span class="comment-count-<?= $metier->getId() ?>"><?= $metier->getCommentCount() ?? 0 ?></span> commentaires
                        </span>
                    </div>
                    
                    <!-- BARRE DE PROGRESSION -->
                    <div class="views-bar mt-2" style="width: 100%; height: 6px; background: rgba(255,255,255,0.3); border-radius: 10px; overflow: hidden;">
                        <div class="views-bar-fill" style="width: <?= min(100, ($views / 50) * 100) ?>%; height: 100%; background: linear-gradient(90deg, #2ed573, #ffa502, #ff4757); border-radius: 10px;">
                        </div>
                    </div>
                    <div style="font-size: 0.65rem; margin-top: 4px; text-align: right; opacity: 0.8;">
                        <?php if ($views < 10): ?>
                            🆕 NOUVEAU
                        <?php elseif ($views < 20): ?>
                            ⭐ POPULAIRE
                        <?php else: ?>
                            🔥 TRENDING
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted"><?= htmlspecialchars($metier->getCategoryName() ?? 'Non catégorisé') ?></p>
                    <p><small><?= nl2br(htmlspecialchars(substr($metier->getDescription() ?? '', 0, 100))) ?>...</small></p>
                    <p><strong>Compétences:</strong> <?= nl2br(htmlspecialchars(substr($metier->getCompetences() ?? '', 0, 60))) ?>...</p>
                    <p><strong>Spécialités:</strong> <?= nl2br(htmlspecialchars(substr($metier->getSpecialites() ?? '', 0, 60))) ?>...</p>
                    <p><strong>Salaire:</strong> <?= $metier->getSalaire() ? number_format($metier->getSalaire(), 2) . ' €' : '-' ?></p>
                </div>
                <div class="card-footer">
                    <a href="index.php?action=edit_metier_public_form&id=<?= $metier->getId() ?>" class="btn btn-warning btn-sm">Modifier</a>
                    <a href="index.php?action=delete_metier_public&id=<?= $metier->getId() ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ?')">Supprimer</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleLike(metierId, element) {
    fetch('index.php?action=toggle_like&id=' + metierId, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const countSpan = document.querySelector('.like-count-' + metierId);
            if (countSpan) countSpan.textContent = data.likes;
            if (data.liked) {
                element.classList.add('liked');
            } else {
                element.classList.remove('liked');
            }
        }
    });
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
        data.comments.forEach(comment => {
            commentsList.innerHTML += `<div><strong>${escapeHtml(comment.author)}</strong> <small>${comment.date}</small><p>${escapeHtml(comment.content)}</p></div><hr>`;
        });
    });
}

function addComment() {
    const metierId = document.getElementById('currentMetierId').value;
    const author = document.getElementById('commentAuthor').value || 'Anonyme';
    const content = document.getElementById('commentContent').value;
    if (!content.trim()) return alert('Veuillez écrire un commentaire');
    
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
            document.querySelector('.comment-count-' + metierId).textContent = data.total;
        }
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<!-- Modal Commentaires -->
<div class="modal fade" id="commentsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: #1a5f7a; color: white;">
                <h5 class="modal-title"><i class="fas fa-comments me-2"></i>Commentaires</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="currentMetierId">
                <div id="commentsList"></div>
                <hr>
                <input type="text" id="commentAuthor" class="form-control mb-2" placeholder="Votre nom">
                <textarea id="commentContent" class="form-control mb-2" rows="2" placeholder="Votre commentaire"></textarea>
                <button onclick="addComment()" class="btn btn-primary w-100">Publier</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>