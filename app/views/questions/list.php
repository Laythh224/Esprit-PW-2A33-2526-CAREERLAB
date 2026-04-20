<?php
$pageTitle = 'Evaluation - Liste des questions';
$pageSubtitle = 'Gestion des questions';
$activeAction = 'list';
$loadDataTables = true;
$dataTableSelector = '#questionsTable';
$flashByCode = [
    'added' => 'Question ajoutee avec succes.',
    'updated' => 'Question mise a jour avec succes.',
    'deleted' => 'Question supprimee avec succes.',
    'reponse_added' => 'Reponse ajoutee avec succes.',
    'reponse_updated' => 'Reponse mise a jour avec succes.',
    'reponse_deleted' => 'Reponse supprimee avec succes.',
    'invalid_id' => 'Identifiant invalide.',
    'not_found' => 'Question introuvable.',
    'db_error' => 'Erreur base de donnees.',
];
$flashMessage = $flashByCode[$message] ?? '';
require __DIR__ . '/partials/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Liste des questions</h4>
                <div class="d-flex gap-2">
                    <a class="btn btn-primary" href="index.php?route=evaluation&action=add">Ajouter une question</a>
                    <a class="btn btn-info" href="index.php?route=evaluation&entity=reponse&action=add">Ajouter une reponse</a>
                    <a class="btn btn-secondary" href="index.php?route=evaluation&entity=reponse&action=list">Lister les reponses</a>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($dbError)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars((string) $dbError, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <?php if ($flashMessage !== ''): ?>
                    <div class="alert alert-info"><?= htmlspecialchars((string) $flashMessage, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table id="questionsTable" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Question</th>
                                <th>id_metier</th>
                                <th style="width: 20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($questions)): ?>
                            <?php foreach ($questions as $question): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) $question->getId(), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars((string) $question->getTexte(), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars((string) $question->getIdMetier(), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <div class="form-button-action">
                                            <a class="btn btn-link btn-primary btn-lg" href="index.php?route=evaluation&action=edit&id=<?= (int) $question->getId(); ?>" title="Modifier">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a class="btn btn-link btn-danger" href="index.php?route=evaluation&action=delete&id=<?= (int) $question->getId(); ?>" onclick="return confirm('Supprimer cette question ?');" title="Supprimer">
                                                <i class="fa fa-times"></i>
                                            </a>
                                            <a class="btn btn-link btn-success" href="index.php?route=evaluation&entity=reponse&action=add&id_question=<?= (int) $question->getId(); ?>" title="Ajouter une reponse">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="3" class="bg-light">
                                        <div class="ps-2">
                                            <strong>Reponses associees :</strong>
                                            <?php $questionId = (int) $question->getId(); ?>
                                            <?php $reponses = $reponsesByQuestion[$questionId] ?? []; ?>

                                            <?php if ($reponses === []): ?>
                                                <div class="text-muted mt-2">Aucune reponse pour cette question.</div>
                                            <?php else: ?>
                                                <ul class="list-group mt-2">
                                                    <?php foreach ($reponses as $reponse): ?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <?= htmlspecialchars((string) $reponse->getTexte(), ENT_QUOTES, 'UTF-8'); ?>
                                                                <?php if ($reponse->isEstCorrecte()): ?>
                                                                    <span class="badge bg-success ms-2">Correcte</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary ms-2">Incorrecte</span>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div>
                                                                <a class="btn btn-link btn-primary" href="index.php?route=evaluation&entity=reponse&action=edit&id=<?= (int) $reponse->getId(); ?>"><i class="fa fa-edit"></i></a>
                                                                <a class="btn btn-link btn-danger" href="index.php?route=evaluation&entity=reponse&action=delete&id=<?= (int) $reponse->getId(); ?>" onclick="return confirm('Supprimer cette reponse ?');"><i class="fa fa-times"></i></a>
                                                            </div>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Aucune question disponible.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
