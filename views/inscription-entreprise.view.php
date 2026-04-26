<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscriptions Entreprises</title>
    <link rel="stylesheet" href="views/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="views/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="views/assets/css/kaiadmin.min.css" />
</head>
<body>
    <div class="wrapper">
        <?php $activeAccountPage = 'inscription-entreprise'; include __DIR__ . '/../views/components/account-sidebar.php'; ?>
        <div class="main-panel">
            <?php include __DIR__ . '/../views/components/account-header.php'; ?>
            <div class="container py-4">
                <div class="row g-4">
                    <!-- Formulaire (col-lg-4) -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Lier une Inscription</h4>
                                <?php if (!empty($message)): ?>
                                    <div class="alert alert-info py-2 small"><?= htmlspecialchars($message) ?></div>
                                <?php endif; ?>
                                <form method="POST" novalidate>
                                    <input type="hidden" name="action" value="add">
                                    <div class="mb-3">
                                        <label class="form-label">Utilisateur</label>
                                        <select name="id_user" class="form-select">
                                            <option value="">Choisir un utilisateur...</option>
                                            <?php foreach ($utilisateurs as $u): ?>
                                                <option value="<?= htmlspecialchars((string)$u['id']) ?>"><?= htmlspecialchars($u['nom'] . ' ' . $u['prenom']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Entreprise</label>
                                        <select name="id_entreprise" class="form-select">
                                            <option value="">Choisir une entreprise...</option>
                                            <?php foreach ($entreprises as $e): ?>
                                                <option value="<?= htmlspecialchars((string)$e['id']) ?>"><?= htmlspecialchars($e['nom_entreprise']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button class="btn btn-primary w-100" type="submit">Enregistrer la liaison</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Table (col-lg-8) -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Liste des inscriptions (Utilisateurs / Entreprises)</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped align-middle">
                                        <thead>
                                            <tr>
                                                <th>Nom Utilisateur</th>
                                                <th>Email Utilisateur</th>
                                                <th>Entreprise Associée</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($inscriptions)): ?>
                                            <tr><td colspan="4" class="text-center text-muted">Aucune inscription.</td></tr>
                                            <?php else: ?>
                                                <?php foreach ($inscriptions as $insc): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($insc['nom'] . ' ' . $insc['prenom']) ?></td>
                                                    <td><?= htmlspecialchars($insc['user_email']) ?></td>
                                                    <td><strong><?= htmlspecialchars($insc['nom_entreprise']) ?></strong></td>
                                                    <td>
                                                        <form method="POST" class="d-inline" novalidate>
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="id_user" value="<?= htmlspecialchars((string)$insc['id_user']) ?>">
                                                            <input type="hidden" name="id_entreprise" value="<?= htmlspecialchars((string)$insc['id_entreprise']) ?>">
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Souhaitez-vous vraiment supprimer cette liaison ?');">Supprimer</button>
                                                        </form>
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
        </div>
    </div>
    <script src="views/assets/js/jquery-3.7.1.min.js"></script>
    <script src="views/assets/js/popper.min.js"></script>
    <script src="views/assets/js/bootstrap.min.js"></script>
    <script src="views/assets/js/kaiadmin.min.js"></script>
</body>
</html>
