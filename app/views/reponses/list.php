<?php
$pageTitle = 'Evaluation - Liste des reponses';
$pageSubtitle = 'Liste des reponses';
$activeAction = 'list';
$loadDataTables = false;
require __DIR__ . '/../questions/partials/header.php';
?>

<style>
    .stats-chart-grid {
        margin-top: 1rem;
    }

    .chart-wrapper {
        max-width: 500px;
        width: 100%;
        height: 260px;
        margin: 0 auto;
    }

    .stats-summary-row .card {
        height: 100%;
    }

    @media (max-width: 768px) {
        .chart-wrapper {
            max-width: 100%;
            height: 220px;
        }
    }
</style>

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

                <form class="row g-3 mb-3" method="get" action="index.php" novalidate>
                    <input type="hidden" name="route" value="evaluation">
                    <input type="hidden" name="entity" value="reponse">
                    <input type="hidden" name="action" value="list">
                    <div class="col-md-3">
                        <label for="search_texte" class="form-label">Recherche par texte</label>
                        <input type="text" class="form-control" id="search_texte" name="search_texte" value="<?= htmlspecialchars((string) ($searchTexte ?? ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Ex: reponse A...">
                    </div>
                    <div class="col-md-3">
                        <label for="search_est_correcte" class="form-label">Filtre est_correcte</label>
                        <select class="form-select" id="search_est_correcte" name="search_est_correcte">
                            <option value="" <?= (($searchEstCorrecte ?? '') === '') ? 'selected' : ''; ?>>Tous</option>
                            <option value="1" <?= (($searchEstCorrecte ?? '') === '1') ? 'selected' : ''; ?>>Oui (correcte)</option>
                            <option value="0" <?= (($searchEstCorrecte ?? '') === '0') ? 'selected' : ''; ?>>Non (incorrecte)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="sort" class="form-label">Tri des reponses</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="id_desc" <?= (($sort ?? 'id_desc') === 'id_desc') ? 'selected' : ''; ?>>id DESC</option>
                            <option value="id_asc" <?= (($sort ?? '') === 'id_asc') ? 'selected' : ''; ?>>id ASC</option>
                            <option value="id_question_asc" <?= (($sort ?? '') === 'id_question_asc') ? 'selected' : ''; ?>>id_question ASC</option>
                            <option value="id_question_desc" <?= (($sort ?? '') === 'id_question_desc') ? 'selected' : ''; ?>>id_question DESC</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Appliquer</button>
                        <a class="btn btn-outline-secondary" href="index.php?route=evaluation&entity=reponse&action=list">Reinitialiser</a>
                    </div>
                </form>

                <!-- réponses -->
                <h5 class="mb-3">Liste des reponses</h5>

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

                <!-- statistiques ici -->
                <div class="row mt-4 stats-summary-row">
                    <div class="col-md-12 mb-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="numbers">
                                    <p class="card-category">Nombre total de reponses</p>
                                    <h4 class="card-title mb-0"><?= (int) ($totalReponses ?? 0); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row stats-chart-grid">
                    <div class="col-lg-6 col-12 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Bar chart : classement des utilisateurs</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-wrapper">
                                    <canvas id="answersBarChart"></canvas>
                                </div>
                                <?php if (!empty($rankingLabels)): ?>
                                    <div class="mt-3 small text-muted">
                                        <?php foreach ($rankingLabels as $index => $userLabel): ?>
                                            <div class="text-center"><?= htmlspecialchars((string) $userLabel, ENT_QUOTES, 'UTF-8'); ?> : <?= htmlspecialchars((string) ($rankingScores[$index] ?? '0/0'), ENT_QUOTES, 'UTF-8'); ?> - <?= number_format((float) ($rankingRates[$index] ?? 0), 2, '.', ''); ?>% (<?= htmlspecialchars((string) ($rankingLevels[$index] ?? 'Debutant'), ENT_QUOTES, 'UTF-8'); ?>)</div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted small mb-0 mt-3 text-center">Aucune tentative utilisateur enregistree pour le classement.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Pie chart : taux de reussite par metier</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-wrapper">
                                    <canvas id="questionsPieChart"></canvas>
                                </div>
                                <?php if (!empty($metierSuccessLabels) && !empty($metierSuccessRates)): ?>
                                    <div class="mt-3 small text-muted">
                                        <?php foreach ($metierSuccessLabels as $index => $metierLabel): ?>
                                            <div class="text-center"><?= htmlspecialchars((string) $metierLabel, ENT_QUOTES, 'UTF-8'); ?> : <?= number_format((float) ($metierSuccessRates[$index] ?? 0), 2, '.', ''); ?>%</div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted small mb-0 mt-3 text-center">Aucune tentative utilisateur enregistree pour calculer les taux de reussite par metier.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/plugin/chart.js/chart.min.js"></script>
<script>
    (function () {
        if (typeof Chart === 'undefined') {
            return;
        }

        var rankingLabels = <?= json_encode($rankingLabels ?? [], JSON_UNESCAPED_SLASHES); ?>;
        var rankingRates = <?= json_encode($rankingRates ?? [], JSON_UNESCAPED_SLASHES); ?>;
        var rankingScores = <?= json_encode($rankingScores ?? [], JSON_UNESCAPED_SLASHES); ?>;
        var rankingLevels = <?= json_encode($rankingLevels ?? [], JSON_UNESCAPED_SLASHES); ?>;
        var rankingColors = <?= json_encode($rankingColors ?? [], JSON_UNESCAPED_SLASHES); ?>;
        var metierLabels = <?= json_encode($metierSuccessLabels ?? [], JSON_UNESCAPED_SLASHES); ?>;
        var metierRates = <?= json_encode($metierSuccessRates ?? [], JSON_UNESCAPED_SLASHES); ?>;

        var displayRankingLabels = rankingLabels.length ? rankingLabels : ['Aucun utilisateur'];
        var displayRankingRates = rankingRates.length ? rankingRates : [0];
        var displayRankingScores = rankingScores.length ? rankingScores : ['0/0'];
        var displayRankingLevels = rankingLevels.length ? rankingLevels : ['Debutant'];
        var displayRankingColors = rankingColors.length ? rankingColors : ['#6c757d'];

        var scoreLabelPlugin = {
            id: 'scoreLabelPlugin',
            afterDatasetsDraw: function (chart) {
                var ctx = chart.ctx;
                var meta = chart.getDatasetMeta(0);

                ctx.save();
                ctx.fillStyle = '#495057';
                ctx.font = '12px sans-serif';
                ctx.textAlign = 'center';

                for (var i = 0; i < meta.data.length; i++) {
                    var barElement = meta.data[i];
                    var score = displayRankingScores[i] || '0/0';
                    ctx.fillText(score, barElement.x, barElement.y - 8);
                }

                ctx.restore();
            }
        };

        var barCanvas = document.getElementById('answersBarChart');

        if (barCanvas) {
            new Chart(barCanvas, {
                type: 'bar',
                data: {
                    labels: displayRankingLabels,
                    datasets: [{
                        label: 'Pourcentage de reussite',
                        data: displayRankingRates,
                        backgroundColor: displayRankingColors,
                        borderColor: displayRankingColors,
                        borderWidth: 1
                    }]
                },
                plugins: [scoreLabelPlugin],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var index = context.dataIndex;
                                    var score = displayRankingScores[index] || '0/0';
                                    var level = displayRankingLevels[index] || 'Debutant';
                                    return 'Reussite: ' + context.parsed.y + '% | Score: ' + score + ' | Niveau: ' + level;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function (value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        var pieCanvas = document.getElementById('questionsPieChart');

        if (pieCanvas) {
            var pieLabels = [];

            for (var i = 0; i < metierLabels.length; i++) {
                var rate = Number(metierRates[i] || 0);
                var rateDisplay = rate.toFixed(2).replace(/\.00$/, '');
                pieLabels.push(metierLabels[i] + ' (' + rateDisplay + '%)');
            }

            var pieData = metierRates.length ? metierRates : [100];

            if (!metierRates.length) {
                pieLabels = ['Aucune donnee'];
            }

            new Chart(pieCanvas, {
                type: 'doughnut',
                data: {
                    labels: pieLabels,
                    datasets: [{
                        data: pieData,
                        backgroundColor: [
                            '#17a2b8',
                            '#fd7e14',
                            '#28a745',
                            '#6f42c1',
                            '#20c997',
                            '#ffc107',
                            '#6c757d'
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var label = context.label || '';
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
    })();
</script>

<?php require __DIR__ . '/../questions/partials/footer.php'; ?>
