<?php
$pageTitle = 'Evaluation - Modifier une reponse';
$pageSubtitle = 'Modifier une reponse';
$activeAction = 'list';
require __DIR__ . '/../questions/partials/header.php';
?>

<div class="row">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Edition de la reponse #<?= (int) $reponse->getId(); ?></h4>
                <a class="btn btn-label-secondary" href="index.php?route=evaluation">Retour a l'evaluation</a>
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

                <form method="post" action="index.php?route=evaluation&entity=reponse&action=edit&id=<?= (int) $reponse->getId(); ?>" novalidate>
                    <input type="hidden" name="id" value="<?= (int) $reponse->getId(); ?>">

                    <div class="form-group">
                        <label for="texte">Texte de la reponse</label>
                        <textarea class="form-control" id="texte" name="texte" rows="3"><?= htmlspecialchars((string) $reponse->getTexte(), ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="id_question">Question associee (id_question)</label>
                        <select class="form-select" id="id_question" name="id_question">
                            <option value="">Choisir une question</option>
                            <?php foreach (($questions ?? []) as $question): ?>
                                <option value="<?= (int) $question->getId(); ?>" <?= ((int) $reponse->getIdQuestion() === (int) $question->getId()) ? 'selected' : ''; ?>>
                                    #<?= (int) $question->getId(); ?> - <?= htmlspecialchars((string) $question->getTexte(), ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label d-block">Est correcte ?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="est_correcte" id="est_correcte_non" value="0" <?= !$reponse->isEstCorrecte() ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="est_correcte_non">Non</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="est_correcte" id="est_correcte_oui" value="1" <?= $reponse->isEstCorrecte() ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="est_correcte_oui">Oui</label>
                        </div>
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

<?php require __DIR__ . '/../questions/partials/footer.php'; ?>
