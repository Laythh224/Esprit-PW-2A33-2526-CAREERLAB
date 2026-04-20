<?php
$pageTitle = 'Evaluation - Liste des reponses';
$pageSubtitle = 'Liste des reponses';
$activeAction = 'list';
$loadDataTables = true;
$dataTableSelector = '#reponsesTable';
require __DIR__ . '/../questions/partials/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Toutes les reponses</h4>
                <a class="btn btn-primary" href="index.php?route=evaluation&entity=reponse&action=add">Ajouter une reponse</a>
            </div>
            <div class="card-body">
                <?php if (!empty($dbError)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars((string) $dbError, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table id="reponsesTable" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Texte</th>
                                <th>est_correcte</th>
                                <th>id_question</th>
                                <th style="width: 20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach (($reponses ?? []) as $reponse): ?>
                            <tr>
                                <td><?= (int) $reponse->getId(); ?></td>
                                <td><?= htmlspecialchars((string) $reponse->getTexte(), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= $reponse->isEstCorrecte() ? 'Oui' : 'Non'; ?></td>
                                <td><?= (int) $reponse->getIdQuestion(); ?></td>
                                <td>
                                    <div class="form-button-action">
                                        <a class="btn btn-link btn-primary btn-lg" href="index.php?route=evaluation&entity=reponse&action=edit&id=<?= (int) $reponse->getId(); ?>"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-link btn-danger" href="index.php?route=evaluation&entity=reponse&action=delete&id=<?= (int) $reponse->getId(); ?>" onclick="return confirm('Supprimer cette reponse ?');"><i class="fa fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../questions/partials/footer.php'; ?>
