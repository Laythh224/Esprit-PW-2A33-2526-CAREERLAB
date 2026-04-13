<?php
require_once 'config.php';

class MetierManager {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    // CREATE - Ajouter un nouveau métier
    public function ajouterMetier($nom, $description, $salaire, $secteur) {
        $sql = "INSERT INTO metiers (nom, description, salaire_moyen, secteur) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $description, $salaire, $secteur]);
    }

    // READ - Récupérer tous les métiers
    public function getAllMetiers() {
        $sql = "SELECT * FROM metiers ORDER BY date_creation DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ - Récupérer un métier par ID
    public function getMetierById($id) {
        $sql = "SELECT * FROM metiers WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE - Modifier un métier
    public function modifierMetier($id, $nom, $description, $salaire, $secteur) {
        $sql = "UPDATE metiers SET nom = ?, description = ?, salaire_moyen = ?, secteur = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $description, $salaire, $secteur, $id]);
    }

    // DELETE - Supprimer un métier
    public function supprimerMetier($id) {
        $sql = "DELETE FROM metiers WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // SEARCH - Rechercher des métiers
    public function rechercherMetiers($searchTerm) {
        $sql = "SELECT * FROM metiers WHERE nom LIKE ? OR description LIKE ? OR secteur LIKE ?";
        $stmt = $this->pdo->prepare($sql);
        $searchPattern = "%$searchTerm%";
        $stmt->execute([$searchPattern, $searchPattern, $searchPattern]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Gestion des actions POST
$metierManager = new MetierManager();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'ajouter':
                if ($metierManager->ajouterMetier(
                    $_POST['nom'],
                    $_POST['description'],
                    $_POST['salaire'],
                    $_POST['secteur']
                )) {
                    $message = "Métier ajouté avec succès !";
                    $messageType = "success";
                } else {
                    $message = "Erreur lors de l'ajout du métier.";
                    $messageType = "error";
                }
                break;

            case 'modifier':
                if ($metierManager->modifierMetier(
                    $_POST['id'],
                    $_POST['nom'],
                    $_POST['description'],
                    $_POST['salaire'],
                    $_POST['secteur']
                )) {
                    $message = "Métier modifié avec succès !";
                    $messageType = "success";
                } else {
                    $message = "Erreur lors de la modification du métier.";
                    $messageType = "error";
                }
                break;

            case 'supprimer':
                if ($metierManager->supprimerMetier($_POST['id'])) {
                    $message = "Métier supprimé avec succès !";
                    $messageType = "success";
                } else {
                    $message = "Erreur lors de la suppression du métier.";
                    $messageType = "error";
                }
                break;
        }
    }
}

// Récupération des données pour affichage
$metiers = isset($_GET['search']) && !empty($_GET['search'])
    ? $metierManager->rechercherMetiers($_GET['search'])
    : $metierManager->getAllMetiers();

// Récupération d'un métier pour modification
$metierToEdit = null;
if (isset($_GET['edit'])) {
    $metierToEdit = $metierManager->getMetierById($_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Métiers - PHP CRUD</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/plugins.min.css">
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css">
    <link rel="stylesheet" href="assets/css/demo.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Gestion des Métiers - PHP CRUD</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Formulaire de recherche -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <form method="GET" class="d-flex">
                                    <input type="text" name="search" class="form-control me-2"
                                           placeholder="Rechercher un métier..."
                                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                    <button type="submit" class="btn btn-outline-primary">Rechercher</button>
                                    <?php if (isset($_GET['search'])): ?>
                                        <a href="metiers.php" class="btn btn-outline-secondary ms-2">Effacer</a>
                                    <?php endif; ?>
                                </form>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMetierModal">
                                    <i class="fas fa-plus"></i> Ajouter un Métier
                                </button>
                            </div>
                        </div>

                        <!-- Tableau des métiers -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom du Métier</th>
                                        <th>Description</th>
                                        <th>Salaire Moyen (€)</th>
                                        <th>Secteur</th>
                                        <th>Date de création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($metiers)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                Aucun métier trouvé.
                                                <?php if (isset($_GET['search'])): ?>
                                                    <a href="metiers.php">Afficher tous les métiers</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($metiers as $metier): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($metier['id']); ?></td>
                                                <td><?php echo htmlspecialchars($metier['nom']); ?></td>
                                                <td><?php echo htmlspecialchars($metier['description'] ?? '-'); ?></td>
                                                <td><?php echo $metier['salaire_moyen'] ? number_format($metier['salaire_moyen'], 2, ',', ' ') . ' €' : '-'; ?></td>
                                                <td><?php echo htmlspecialchars($metier['secteur'] ?? '-'); ?></td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($metier['date_creation'])); ?></td>
                                                <td>
                                                    <a href="?edit=<?php echo $metier['id']; ?>" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i> Modifier
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $metier['id']; ?>, '<?php echo htmlspecialchars($metier['nom']); ?>')">
                                                        <i class="fas fa-trash"></i> Supprimer
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter/Modifier Métier -->
    <div class="modal fade" id="addMetierModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <?php echo $metierToEdit ? 'Modifier le Métier' : 'Ajouter un Métier'; ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <?php if ($metierToEdit): ?>
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($metierToEdit['id']); ?>">
                            <input type="hidden" name="action" value="modifier">
                        <?php else: ?>
                            <input type="hidden" name="action" value="ajouter">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom du Métier *</label>
                            <input type="text" class="form-control" id="nom" name="nom" required
                                   value="<?php echo $metierToEdit ? htmlspecialchars($metierToEdit['nom']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php
                                echo $metierToEdit ? htmlspecialchars($metierToEdit['description']) : '';
                            ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="salaire" class="form-label">Salaire Moyen (€)</label>
                            <input type="number" class="form-control" id="salaire" name="salaire" step="0.01" min="0"
                                   value="<?php echo $metierToEdit ? htmlspecialchars($metierToEdit['salaire_moyen']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="secteur" class="form-label">Secteur</label>
                            <select class="form-control" id="secteur" name="secteur">
                                <option value="">Sélectionner un secteur</option>
                                <option value="Informatique" <?php echo ($metierToEdit && $metierToEdit['secteur'] === 'Informatique') ? 'selected' : ''; ?>>Informatique</option>
                                <option value="Commerce" <?php echo ($metierToEdit && $metierToEdit['secteur'] === 'Commerce') ? 'selected' : ''; ?>>Commerce</option>
                                <option value="Industrie" <?php echo ($metierToEdit && $metierToEdit['secteur'] === 'Industrie') ? 'selected' : ''; ?>>Industrie</option>
                                <option value="Santé" <?php echo ($metierToEdit && $metierToEdit['secteur'] === 'Santé') ? 'selected' : ''; ?>>Santé</option>
                                <option value="Éducation" <?php echo ($metierToEdit && $metierToEdit['secteur'] === 'Éducation') ? 'selected' : ''; ?>>Éducation</option>
                                <option value="Finance" <?php echo ($metierToEdit && $metierToEdit['secteur'] === 'Finance') ? 'selected' : ''; ?>>Finance</option>
                                <option value="Autre" <?php echo ($metierToEdit && $metierToEdit['secteur'] === 'Autre') ? 'selected' : ''; ?>>Autre</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <?php echo $metierToEdit ? 'Modifier' : 'Ajouter'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer le métier "<span id="deleteMetierName"></span>" ?</p>
                    <p class="text-danger">Cette action est irréversible.</p>
                </div>
                <form method="POST" id="deleteForm">
                    <input type="hidden" name="action" value="supprimer">
                    <input type="hidden" name="id" id="deleteMetierId">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script>
        // Afficher le modal d'ajout/modification si on vient de la page d'édition
        <?php if ($metierToEdit): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('addMetierModal'));
                modal.show();
            });
        <?php endif; ?>

        // Fonction de confirmation de suppression
        function confirmDelete(id, name) {
            document.getElementById('deleteMetierId').value = id;
            document.getElementById('deleteMetierName').textContent = name;
            var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>
</body>
</html>