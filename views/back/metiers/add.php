<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un métier - BackOffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4>Ajouter un métier</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?action=add_metier">
                        <div class="mb-3">
                            <label class="form-label">Titre *</label>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
                            <small class="text-muted">Minimum 3 caractères</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Compétences</label>
                                <textarea name="competences" class="form-control" rows="3"><?= htmlspecialchars($_POST['competences'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Spécialités</label>
                                <textarea name="specialites" class="form-control" rows="3"><?= htmlspecialchars($_POST['specialites'] ?? '') ?></textarea>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Salaire (€)</label>
                            <input type="text" name="salaire" class="form-control" value="<?= htmlspecialchars($_POST['salaire'] ?? '') ?>">
                            <small class="text-muted">Nombre positif uniquement</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Catégorie</label>
                            <select name="category_id" class="form-control">
                                <option value="">-- Sans catégorie --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat->getId() ?>"><?= htmlspecialchars($cat->getName()) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="index.php?action=admin_metiers" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>