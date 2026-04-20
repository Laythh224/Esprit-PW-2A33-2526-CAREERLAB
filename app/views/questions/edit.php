<?php
$pageTitle = 'Evaluation - Modifier une question';
$pageSubtitle = 'Modifier une question';
$activeAction = 'edit';
$loadValidationJs = true;
require __DIR__ . '/partials/header.php';
?>

<div class="row">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Edition de la question #<?= (int) $question->getId(); ?></h4>
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

                <form method="post" action="index.php?route=evaluation&action=edit&id=<?= (int) $question->getId(); ?>" onsubmit="return validateQuestion();">
                    <input type="hidden" name="id" value="<?= (int) $question->getId(); ?>">

                    <div class="form-group">
                        <label for="question">Texte de la question</label>
                        <textarea class="form-control" id="question" name="question" rows="4" required><?= htmlspecialchars((string) $question->getTexte(), ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="id_metier">id_metier</label>
                        <input class="form-control" type="number" id="id_metier" name="id_metier" min="1" required value="<?= htmlspecialchars((string) $question->getIdMetier(), ENT_QUOTES, 'UTF-8'); ?>">
                        <small class="form-text text-muted">Exemples: 1 = medecin, 2 = developpeur, 3 = designer.</small>
                    </div>

                    <div class="card-action p-0 pt-2">
                        <button class="btn btn-success" type="submit">Mettre a jour</button>
                        <a class="btn btn-danger" href="index.php?route=evaluation">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
