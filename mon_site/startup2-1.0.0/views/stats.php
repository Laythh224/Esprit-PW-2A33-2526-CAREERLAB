<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Métiers - PHP CRUD</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/plugins.min.css">
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css">
    <link rel="stylesheet" href="../assets/css/demo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            Statistiques des Métiers
                        </h3>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($stats) || $stats['total_metiers'] == 0): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">Aucune statistique disponible</h4>
                                <p class="text-muted">Ajoutez des métiers pour voir les statistiques.</p>
                                <a href="?action=create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Ajouter un métier
                                </a>
                            </div>
                        <?php else: ?>
                            <!-- Cartes de statistiques principales -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <i class="fas fa-briefcase fa-2x mb-2"></i>
                                            <h4 class="card-title"><?php echo htmlspecialchars($stats['total_metiers']); ?></h4>
                                            <p class="card-text">Total des métiers</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <i class="fas fa-building fa-2x mb-2"></i>
                                            <h4 class="card-title"><?php echo htmlspecialchars($stats['secteurs_count']); ?></h4>
                                            <p class="card-text">Secteurs représentés</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <i class="fas fa-euro-sign fa-2x mb-2"></i>
                                            <h4 class="card-title"><?php echo number_format($stats['salaire_moyen_global'], 0, ',', ' '); ?> €</h4>
                                            <p class="card-text">Salaire moyen global</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <i class="fas fa-calendar fa-2x mb-2"></i>
                                            <h4 class="card-title"><?php echo date('d/m/Y', strtotime($stats['plus_recent'])); ?></h4>
                                            <p class="card-text">Dernier ajout</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Répartition par secteur -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-chart-pie me-2"></i>Répartition par secteur
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <?php if (empty($stats['secteurs'])): ?>
                                                <p class="text-muted text-center">Aucun secteur défini</p>
                                            <?php else: ?>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Secteur</th>
                                                                <th>Nombre</th>
                                                                <th>Pourcentage</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($stats['secteurs'] as $secteur => $count): ?>
                                                                <tr>
                                                                    <td><?php echo htmlspecialchars($secteur ?: 'Non défini'); ?></td>
                                                                    <td><?php echo htmlspecialchars($count); ?></td>
                                                                    <td>
                                                                        <div class="progress" style="height: 20px;">
                                                                            <div class="progress-bar" role="progressbar"
                                                                                 style="width: <?php echo ($count / $stats['total_metiers']) * 100; ?>%">
                                                                                <?php echo round(($count / $stats['total_metiers']) * 100, 1); ?>%
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Statistiques salariales -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-euro-sign me-2"></i>Statistiques salariales
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <?php if ($stats['salaire_moyen_global'] == 0): ?>
                                                <p class="text-muted text-center">Aucun salaire défini</p>
                                            <?php else: ?>
                                                <div class="row text-center">
                                                    <div class="col-6">
                                                        <div class="border-end">
                                                            <h6 class="text-success">Salaire minimum</h6>
                                                            <h4><?php echo number_format($stats['salaire_min'], 0, ',', ' '); ?> €</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <h6 class="text-danger">Salaire maximum</h6>
                                                        <h4><?php echo number_format($stats['salaire_max'], 0, ',', ' '); ?> €</h4>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6 class="text-center">Répartition des salaires</h6>
                                                        <div class="mt-3">
                                                            <?php
                                                            $ranges = [
                                                                '0-25000' => 0,
                                                                '25001-35000' => 0,
                                                                '35001-45000' => 0,
                                                                '45001-60000' => 0,
                                                                '60001+' => 0
                                                            ];

                                                            foreach ($stats['salaires_detail'] as $salaire) {
                                                                if ($salaire >= 0 && $salaire <= 25000) $ranges['0-25000']++;
                                                                elseif ($salaire <= 35000) $ranges['25001-35000']++;
                                                                elseif ($salaire <= 45000) $ranges['35001-45000']++;
                                                                elseif ($salaire <= 60000) $ranges['45001-60000']++;
                                                                else $ranges['60001+']++;
                                                            }
                                                            ?>

                                                            <?php foreach ($ranges as $range => $count): ?>
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <span class="small"><?php echo htmlspecialchars($range); ?> €</span>
                                                                    <div class="flex-grow-1 mx-2">
                                                                        <div class="progress" style="height: 8px;">
                                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                                 style="width: <?php echo $stats['total_metiers'] > 0 ? ($count / $stats['total_metiers']) * 100 : 0; ?>%">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <span class="small text-muted"><?php echo htmlspecialchars($count); ?></span>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Métier le mieux payé et le moins payé -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card border-success">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="card-title mb-0">
                                                <i class="fas fa-trophy me-2"></i>Métier le mieux payé
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <?php if ($stats['meilleur_salaire']): ?>
                                                <h5 class="card-title"><?php echo htmlspecialchars($stats['meilleur_salaire']['nom']); ?></h5>
                                                <p class="card-text">
                                                    <strong><?php echo number_format($stats['meilleur_salaire']['salaire_moyen'], 0, ',', ' '); ?> €</strong>
                                                    <?php if ($stats['meilleur_salaire']['secteur']): ?>
                                                        <br><small class="text-muted"><?php echo htmlspecialchars($stats['meilleur_salaire']['secteur']); ?></small>
                                                    <?php endif; ?>
                                                </p>
                                            <?php else: ?>
                                                <p class="text-muted">Aucun salaire défini</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-warning">
                                        <div class="card-header bg-warning">
                                            <h6 class="card-title mb-0">
                                                <i class="fas fa-coins me-2"></i>Métier le moins payé
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <?php if ($stats['pire_salaire']): ?>
                                                <h5 class="card-title"><?php echo htmlspecialchars($stats['pire_salaire']['nom']); ?></h5>
                                                <p class="card-text">
                                                    <strong><?php echo number_format($stats['pire_salaire']['salaire_moyen'], 0, ',', ' '); ?> €</strong>
                                                    <?php if ($stats['pire_salaire']['secteur']): ?>
                                                        <br><small class="text-muted"><?php echo htmlspecialchars($stats['pire_salaire']['secteur']); ?></small>
                                                    <?php endif; ?>
                                                </p>
                                            <?php else: ?>
                                                <p class="text-muted">Aucun salaire défini</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Derniers métiers ajoutés -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-clock me-2"></i>Derniers métiers ajoutés
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($stats['derniers_metiers'])): ?>
                                        <p class="text-muted text-center">Aucun métier trouvé</p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Métier</th>
                                                        <th>Secteur</th>
                                                        <th>Date d'ajout</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach (array_slice($stats['derniers_metiers'], 0, 5) as $metier): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($metier['nom']); ?></td>
                                                            <td><?php echo htmlspecialchars($metier['secteur'] ?: '-'); ?></td>
                                                            <td><?php echo date('d/m/Y H:i', strtotime($metier['date_creation'])); ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
</body>
</html>