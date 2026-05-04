<?php
$metiers = $metiers ?? [];

// Calcul des statistiques
$total_metiers = count($metiers);
$total_salaire = 0;
$salaire_min = null;
$salaire_max = null;
$categories_stats = [];

foreach ($metiers as $m) {
    $salaire = $m->getSalaire();
    if ($salaire && $salaire > 0) {
        $total_salaire += $salaire;
        if ($salaire_min === null || $salaire < $salaire_min) $salaire_min = $salaire;
        if ($salaire_max === null || $salaire > $salaire_max) $salaire_max = $salaire;
    }
    
    $catName = $m->getCategoryName() ?? 'Non catégorisé';
    if (!isset($categories_stats[$catName])) {
        $categories_stats[$catName] = 0;
    }
    $categories_stats[$catName]++;
}

$salaire_moyen = ($total_metiers > 0 && $total_salaire > 0) ? round($total_salaire / $total_metiers) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BackOffice - Career Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <a href="index.php?action=admin_candidatures" class="btn btn-info">
    <i class="fas fa-users"></i> Candidatures
</a>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background: #1a1a2e; min-height: 100vh; }
        .sidebar a { color: white; text-decoration: none; }
        .logo-img { height: 45px; display: block; margin: 0 auto; }
        .sidebar-header { text-align: center; padding: 15px 0; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .stat-card { border: none; border-radius: 15px; transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon { font-size: 2.5rem; opacity: 0.7; }
        .chart-container { max-height: 300px; }
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
                
                <!-- Carte 1 - Total métiers -->
                <div class="col-md-3 mb-3">
                    <div class="card stat-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Total métiers</h6>
                                    <h2 class="mb-0"><?= $total_metiers ?></h2>
                                </div>
                                <i class="fas fa-briefcase stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Carte 2 - Salaire moyen -->
                <div class="col-md-3 mb-3">
                    <div class="card stat-card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Salaire moyen</h6>
                                    <h2 class="mb-0"><?= number_format($salaire_moyen, 0, ',', ' ') ?> €</h2>
                                </div>
                                <i class="fas fa-euro-sign stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Carte 3 - Salaire min -->
                <div class="col-md-3 mb-3">
                    <div class="card stat-card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Salaire minimum</h6>
                                    <h2 class="mb-0"><?= $salaire_min ? number_format($salaire_min, 0, ',', ' ') . ' €' : '-' ?></h2>
                                </div>
                                <i class="fas fa-arrow-down stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Carte 4 - Salaire max -->
                <div class="col-md-3 mb-3">
                    <div class="card stat-card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Salaire maximum</h6>
                                    <h2 class="mb-0"><?= $salaire_max ? number_format($salaire_max, 0, ',', ' ') . ' €' : '-' ?></h2>
                                </div>
                                <i class="fas fa-arrow-up stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Graphiques -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Répartition par catégorie</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="categoryChart" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Top 5 salaires</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="salaryChart" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Liste des métiers -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-briefcase me-2"></i> Gestion des Métiers</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="index.php?action=add_metier_form" class="btn btn-success">
                            <i class="fas fa-plus"></i> Ajouter un métier
                        </a>
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
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Compétences</th>
                                    <th>Spécialités</th>
                                    <th>Salaire</th>
                                    <th>Catégorie</th>
                                    <th> Vues</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($metiers as $m): ?>
                                <tr>
                                    <td><?= $m->getId() ?></td>
                                    <td><?= htmlspecialchars($m->getTitle()) ?></td>
                                    <td><?= htmlspecialchars(substr($m->getDescription() ?? '', 0, 50)) ?>...</td>
                                    <td><?= htmlspecialchars(substr($m->getCompetences() ?? '', 0, 30)) ?>...</td>
                                    <td><?= htmlspecialchars(substr($m->getSpecialites() ?? '', 0, 30)) ?>...</td>
                                    <td><?= $m->getSalaire() ? number_format($m->getSalaire(), 2) . ' €' : '-' ?></td>
                                    <td><?= htmlspecialchars($m->getCategoryName() ?? '-') ?></td>
                                    <td class="text-center">
                                        <?php if ($m->getViews() > 20): ?>
                                            <span class="badge bg-danger"><?= $m->getViews() ?> </span>
                                        <?php elseif ($m->getViews() > 10): ?>
                                            <span class="badge bg-warning text-dark"><?= $m->getViews() ?> </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= $m->getViews() ?> </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="index.php?action=edit_metier_form&id=<?= $m->getId() ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <a href="index.php?action=delete_metier&id=<?= $m->getId() ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ?')">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Graphique des catégories
    const categories = <?= json_encode(array_keys($categories_stats)) ?>;
    const counts = <?= json_encode(array_values($categories_stats)) ?>;
    
    const ctx1 = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: categories,
            datasets: [{
                data: counts,
                backgroundColor: ['#667eea', '#764ba2', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
    
    // Graphique des salaires (top 5)
    <?php
    $topSalaries = array_slice($metiers, 0, 5);
    $salaryTitles = [];
    $salaryValues = [];
    foreach ($topSalaries as $m) {
        if ($m->getSalaire()) {
            $salaryTitles[] = addslashes($m->getTitle());
            $salaryValues[] = $m->getSalaire();
        }
    }
    ?>
    const salaryTitles = <?= json_encode($salaryTitles) ?>;
    const salaryValues = <?= json_encode($salaryValues) ?>;
    
    const ctx2 = document.getElementById('salaryChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: salaryTitles,
            datasets: [{
                label: 'Salaire (€)',
                data: salaryValues,
                backgroundColor: '#667eea',
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: function(value) { return value + ' €'; } }
                }
            }
        }
    });
</script>
</body>
</html>