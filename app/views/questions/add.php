<?php
$pageTitle = 'Evaluation - Ajouter une question';
$pageSubtitle = 'Ajouter une question';
$activeAction = 'add';
require __DIR__ . '/partials/header.php';
?>

<div class="row">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Nouvelle question</h4>
                <a class="btn btn-label-secondary" href="index.php?route=evaluation">Retour a la liste</a>
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

                <form method="post" action="index.php?route=evaluation&action=add" novalidate>
                    <div class="form-group">
                        <label for="question">Texte de la question</label>
                        <textarea class="form-control" id="question" name="question" rows="4"><?= htmlspecialchars((string) ($texte ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="id_metier">id_metier</label>
                        <input class="form-control" type="text" id="id_metier" name="id_metier" value="<?= htmlspecialchars((string) ($idMetierInput ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="card-action p-0 pt-2">
                        <button class="btn btn-success" type="submit">Ajouter</button>
                        <a class="btn btn-danger" href="index.php?route=evaluation">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
