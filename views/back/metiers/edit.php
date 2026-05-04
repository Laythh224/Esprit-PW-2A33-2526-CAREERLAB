<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un métier - Career Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4>Modifier le métier</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?action=edit_metier&id=<?= $metier->getId() ?>">
                        <div class="mb-3">
                            <label class="form-label">Titre *</label>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($metier->getTitle()) ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($metier->getDescription() ?? '') ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Salaire (€)</label>
                            <input type="text" name="salaire" class="form-control" value="<?= htmlspecialchars($metier->getSalaire() ?? '') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Catégorie</label>
                            <select name="category_id" class="form-control">
                                <option value="">-- Sans catégorie --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat->getId() ?>" <?= ($metier->getCategoryId() == $cat->getId()) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat->getName()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="index.php?action=admin_metiers" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>