<?php
$categories = $categories ?? [];

// Calcul des statistiques pour les catégories
$total_categories = count($categories);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BackOffice - Catégories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background: #1a1a2e; min-height: 100vh; }
        .sidebar a { color: white; text-decoration: none; }
        .logo-img { height: 45px; display: block; margin: 0 auto; }
        .sidebar-header { text-align: center; padding: 15px 0; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .stat-card { border: none; border-radius: 15px; transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon { font-size: 2.5rem; opacity: 0.7; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-3">
            <div class="sidebar-header">
                <img src="assets/img/logo.png" alt="Career Lab" class="logo-img">
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="index.php?action=admin_metiers" class="text-white"><i class="fas fa-briefcase me-2"></i> Métiers</a></li>
                <li class="nav-item mb-2"><a href="index.php?action=admin_categories" class="text-white"><i class="fas fa-tags me-2"></i> Catégories</a></li>
                <li class="nav-item mb-2"><a href="index.php" class="text-white"><i class="fas fa-globe me-2"></i> Front Office</a></li>
            </ul>
        </div>
        
        <!-- Main content -->
        <div class="col-md-10 p-4">
            <!-- STATISTIQUES -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h3 class="mb-3"><i class="fas fa-chart-line"></i> Tableau de bord</h3>
                </div>
                
                <!-- Carte 1 - Total catégories -->
                <div class="col-md-4 mb-3">
                    <div class="card stat-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Total catégories</h6>
                                    <h2 class="mb-0"><?= $total_categories ?></h2>
                                </div>
                                <i class="fas fa-tags stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Carte 2 - Dernière catégorie -->
                <div class="col-md-4 mb-3">
                    <div class="card stat-card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Catégories disponibles</h6>
                                    <h2 class="mb-0"><?= $total_categories ?></h2>
                                </div>
                                <i class="fas fa-list stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Carte 3 - Métiers liés -->
                <div class="col-md-4 mb-3">
                    <div class="card stat-card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Actions possibles</h6>
                                    <h2 class="mb-0">Ajouter / Supprimer</h2>
                                </div>
                                <i class="fas fa-cogs stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gestion des Catégories -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-tags me-2"></i> Gestion des Catégories</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-globe"></i> Retour au site
                        </a>
                    </div>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success'] ?><?php unset($_SESSION['success']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['errors'])): ?>
                        <div class="alert alert-danger"><?= implode('<br>', $_SESSION['errors']) ?><?php unset($_SESSION['errors']); ?></div>
                    <?php endif; ?>
                    
                    <!-- Formulaire ajout catégorie -->
                   <!-- Formulaire ajout catégorie -->
<div class="card mb-4 border-success">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Ajouter une catégorie</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="index.php?action=add_category" class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Nom de la catégorie <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" placeholder="Ex: Informatique, Santé, Finance..." required>
                <small class="text-muted">Minimum 3 caractères, maximum 100 caractères</small>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Icône / Couleur</label>
                <select class="form-control" name="icon">
                    <option value="fas fa-code"> Technologie</option>
                    <option value="fas fa-heartbeat"> Santé</option>
                    <option value="fas fa-chart-line"> Finance</option>
                    <option value="fas fa-graduation-cap"> Éducation</option>
                    <option value="fas fa-shopping-cart"> Commerce</option>
                    <option value="fas fa-industry"> Industrie</option>
                    <option value="fas fa-paint-brush"> Design</option>
                    <option value="fas fa-users"> Marketing</option>
                </select>
                <small class="text-muted">Icône qui représentera la catégorie</small>
            </div>
            <div class="col-md-12">
                <label class="form-label fw-bold">Description de la catégorie</label>
                <textarea class="form-control" name="description" rows="2" placeholder="Décrivez brièvement cette catégorie..."></textarea>
                <small class="text-muted">Optionnel - Description de la catégorie</small>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-save"></i> Ajouter la catégorie
                </button>
            </div>
        </form>
    </div>
</div>
                    <!-- Liste des catégories -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th><i class="fas fa-tag"></i> Nom de la catégorie</th>
                                    <th><i class="fas fa-calendar"></i> Date de création</th>
                                    <th><i class="fas fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Aucune catégorie trouvée.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($categories as $cat): ?>
                                    <tr>
                                        <td><?= $cat->getId() ?></td>
                                        <td><strong><?= htmlspecialchars($cat->getName()) ?></strong></td>
                                        <td><?= $cat->getCreatedAt() ? date('d/m/Y H:i', strtotime($cat->getCreatedAt())) : '-' ?></td>
                                        <td>
                                            <a href="index.php?action=delete_category&id=<?= $cat->getId() ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Supprimer définitivement la catégorie « <?= htmlspecialchars($cat->getName()) ?> » ?\n\nAttention : Les métiers associés ne seront pas supprimés mais n\'auront plus de catégorie.')">
                                                <i class="fas fa-trash-alt"></i> Supprimer
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if (!empty($categories)): ?>
                    <div class="mt-3 text-muted">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            Total : <strong><?= count($categories) ?></strong> catégorie(s)
                        </small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>