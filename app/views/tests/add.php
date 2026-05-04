<?php
$pageTitle = 'Créer un test';
$pageSubtitle = 'Créer un test';
$activeAction = 'add_test';
require __DIR__ . '/../partials/header.php';
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Nouveau test</h4>
                <a class="btn btn-label-secondary" href="index.php?route=home">Retour</a>
            </div>
            <div class="card-body">
                <?php if (!empty($dbError)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars((string) $dbError, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8'); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="index.php?route=test" novalidate>
                    <div class="form-group">
                        <label for="date">Date du test</label>
                        <input class="form-control" type="date" id="date" name="date" value="<?= htmlspecialchars((string) ($date ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="heure_debut">Heure de début</label>
                        <input class="form-control" type="time" id="heure_debut" name="heure_debut" value="<?= htmlspecialchars((string) ($heure_debut ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="heure_fin">Heure de fin</label>
                        <input class="form-control" type="time" id="heure_fin" name="heure_fin" value="<?= htmlspecialchars((string) ($heure_fin ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="id_metier">Choix du métier (id_metier)</label>
                        <input class="form-control" type="number" id="id_metier" name="id_metier" min="1" value="<?= htmlspecialchars((string) ($idMetierInput ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="card-action p-0 pt-2">
                        <button class="btn btn-success" type="submit">Créer le test</button>
                        <a class="btn btn-danger" href="index.php?route=home">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
