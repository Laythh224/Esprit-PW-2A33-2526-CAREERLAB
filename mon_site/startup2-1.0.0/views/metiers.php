<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Métiers - PHP CRUD</title>
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
                            <i class="fas fa-briefcase me-2"></i>
                            Gestion des Métiers - PHP CRUD
                        </h3>
                        <a href="?action=create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un Métier
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Messages de succès -->
                        <?php if (!empty($messages)): ?>
                            <?php foreach ($messages as $message): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?php echo htmlspecialchars($message); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Messages d'erreur -->
                        <?php if (!empty($errors)): ?>
                            <?php foreach ($errors as $error): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php echo htmlspecialchars($error); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Formulaire de recherche -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <form method="GET" class="d-flex" id="searchForm">
                                    <input type="hidden" name="action" value="search">
                                    <input type="text" name="q" class="form-control me-2"
                                           placeholder="Rechercher par ID, nom, description, salaire ou secteur..."
                                           value="<?php echo htmlspecialchars($search_query); ?>">
                                    <button type="submit" class="btn btn-outline-primary me-2">
                                        <i class="fas fa-search"></i> Rechercher
                                    </button>
                                    <?php if (!empty($search_query)): ?>
                                        <a href="index.php" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i> Effacer
                                        </a>
                                    <?php endif; ?>
                                </form>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="btn-group" role="group">
                                    <a href="index.php" class="btn btn-outline-secondary <?php echo $current_action === 'list' ? 'active' : ''; ?>">
                                        <i class="fas fa-list"></i> Liste
                                    </a>
                                    <a href="?action=stats" class="btn btn-outline-info">
                                        <i class="fas fa-chart-bar"></i> Statistiques
                                    </a>
                                    <a href="diagnostic.php" class="btn btn-outline-warning" target="_blank">
                                        <i class="fas fa-stethoscope"></i> Diagnostic
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Tableau des métiers -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-hashtag"></i> ID</th>
                                        <th><i class="fas fa-briefcase"></i> Nom du Métier</th>
                                        <th><i class="fas fa-align-left"></i> Description</th>
                                        <th><i class="fas fa-euro-sign"></i> Salaire Moyen</th>
                                        <th><i class="fas fa-building"></i> Secteur</th>
                                        <th><i class="fas fa-calendar"></i> Créé le</th>
                                        <th><i class="fas fa-cogs"></i> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($metiers)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-5">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <br>
                                                <?php if (!empty($search_query)): ?>
                                                    Aucun métier trouvé pour "<strong><?php echo htmlspecialchars($search_query); ?></strong>"
                                                    <br><a href="index.php">Afficher tous les métiers</a>
                                                <?php else: ?>
                                                    Aucun métier dans la base de données.
                                                    <br><a href="?action=create">Ajouter le premier métier</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($metiers as $metier): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($metier['id']); ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($metier['nom']); ?></strong>
                                                </td>
                                                <td>
                                                    <?php
                                                    $description = $metier['description'] ?? '';
                                                    echo htmlspecialchars(strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if ($metier['salaire_moyen']): ?>
                                                        <span class="badge bg-success">
                                                            <?php echo number_format($metier['salaire_moyen'], 0, ',', ' '); ?> €
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($metier['secteur']): ?>
                                                        <span class="badge bg-primary">
                                                            <?php echo htmlspecialchars($metier['secteur']); ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($metier['date_creation'])); ?></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="?action=edit&id=<?php echo $metier['id']; ?>"
                                                           class="btn btn-sm btn-warning" title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                                onclick="confirmDelete(<?php echo $metier['id']; ?>, '<?php echo htmlspecialchars(addslashes($metier['nom'])); ?>')"
                                                                title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if (!empty($metiers)): ?>
                            <div class="mt-3 text-muted">
                                <small>Affichage de <?php echo count($metiers); ?> métier(s)</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Confirmer la suppression
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer le métier <strong id="deleteMetierName"></strong> ?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Supprimer
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script>
        // Fonction de confirmation de suppression
        function confirmDelete(id, name) {
            document.getElementById('deleteMetierName').textContent = name;
            document.getElementById('confirmDeleteBtn').href = '?action=delete&id=' + id;
            var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        // Auto-focus sur le champ de recherche
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="q"]');
            if (searchInput && window.location.search.includes('action=search')) {
                searchInput.focus();
                searchInput.select();
            }
        });

        // Soumission automatique de la recherche après 500ms
        let searchTimeout;
        document.querySelector('input[name="q"]').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });
    </script>
</body>
</html>