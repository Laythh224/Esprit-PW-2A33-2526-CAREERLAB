<?php
$metiers = $metiers ?? [];
$categories = $categories ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Career Lab - Métiers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .bg-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-metier {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 25px;
            overflow: hidden;
            background: white;
        }
        .card-metier:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .card-header-metier {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
        }
        .badge-category {
            background-color: #e9ecef;
            color: #495057;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .salary {
            color: #28a745;
            font-weight: bold;
            font-size: 1.1rem;
        }
        footer {
            background: #1a1a2e;
            color: white;
            padding: 20px 0;
            margin-top: 40px;
            text-align: center;
        }
        .search-bar {
            max-width: 500px;
            margin: 0 auto 30px auto;
        }
        .search-bar input {
            border-radius: 30px 0 0 30px;
            border: 1px solid #ddd;
            padding: 12px 20px;
        }
        .search-bar button {
            border-radius: 0 30px 30px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 25px;
            color: white;
        }
        .search-bar button:hover {
            opacity: 0.9;
        }
        .form-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 40px;
        }
        .form-card h3 {
            color: #667eea;
            margin-bottom: 20px;
            font-weight: 700;
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 600;
        }
        .btn-custom:hover {
            opacity: 0.9;
            color: white;
        }
        .btn-outline-custom {
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 30px;
            padding: 8px 20px;
        }
        .btn-outline-custom:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fa fa-user-tie me-2"></i>Career Lab
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">services</a>
                    <ul class="dropdown-menu">
                        <!-- <li><a class="dropdown-item" href="#">utilisateur</a></li> -->
                        <li><a class="dropdown-item active" href="index.php?action=metiers">métiers</a></li>
                        <!-- <li><a class="dropdown-item" href="#">evaluation</a></li> -->
                        <!-- <li><a class="dropdown-item" href="#">les offres</a></li> -->
                        <!-- <li><a class="dropdown-item" href="#">E_learning</a></li> -->
                        <!-- <li><a class="dropdown-item" href="#">blog</a></li> -->
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light ms-2" href="index.php?action=admin_metiers">
                        <i class="fas fa-lock"></i> Administration
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Messages flash -->
<div class="container mt-3">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle"></i> <?= implode('<br>', $_SESSION['errors']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>
</div>

<div class="container py-4">
    
    <!-- Titre -->
    <div class="text-center mb-4">
        <h1 class="display-5 fw-bold"><i class="fas fa-chart-line"></i> Les opportunités de carrière</h1>
        <p class="lead">Découvrez les métiers qui correspondent à vos compétences</p>
    </div>
    
    <!-- Barre de recherche -->
    <div class="search-bar">
        <form method="GET" action="index.php" class="d-flex">
            <input type="hidden" name="action" value="search">
            <input type="text" name="search" class="form-control" placeholder="Rechercher un métier..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit"><i class="fas fa-search"></i> Rechercher</button>
        </form>
    </div>
    
    <!-- Formulaire AJOUTER -->
    <div class="form-card">
        <h3><i class="fas fa-plus-circle"></i> Proposer un nouveau métier</h3>
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
            <button type="submit" class="btn-custom w-100"><i class="fas fa-rocket"></i> Proposer ce métier</button>
        </form>
    </div>
    
    <!-- Liste des métiers -->
    <div class="row">
        <?php if (empty($metiers)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center">Aucun métier trouvé.</div>
            </div>
        <?php else: ?>
            <?php foreach ($metiers as $metier): ?>
            <div class="col-md-4 mb-4">
                <div class="card-metier h-100 shadow-sm">
                    <div class="card-header-metier">
                        <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i><?= htmlspecialchars($metier->getTitle()) ?></h5>
                    </div>
                    <div class="card-body">
                        <p><span class="badge-category"><i class="fas fa-tag me-1"></i> <?= htmlspecialchars($metier->getCategoryName() ?? 'Non catégorisé') ?></span></p>
                        <p><strong>📝 Description :</strong><br><?= nl2br(htmlspecialchars(substr($metier->getDescription() ?? '', 0, 100))) ?>...</p>
                        <p><strong>⚡ Compétences :</strong><br><?= nl2br(htmlspecialchars(substr($metier->getCompetences() ?? '', 0, 80))) ?>...</p>
                        <p><strong>🎯 Spécialités :</strong><br><?= nl2br(htmlspecialchars(substr($metier->getSpecialites() ?? '', 0, 80))) ?>...</p>
                        <p class="salary mt-2"><i class="fas fa-euro-sign"></i> <?= $metier->getSalaire() ? number_format($metier->getSalaire(), 2) . ' €' : 'Non spécifié' ?></p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <a href="index.php?action=edit_metier_public_form&id=<?= $metier->getId() ?>" class="btn-outline-custom btn-sm"><i class="fas fa-edit"></i> Modifier</a>
                        <a href="index.php?action=delete_metier_public&id=<?= $metier->getId() ?>" class="btn btn-danger btn-sm ms-2" onclick="return confirm('Supprimer ce métier ?')"><i class="fas fa-trash"></i> Supprimer</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>&copy; 2024 Career Lab - Tous droits réservés</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>