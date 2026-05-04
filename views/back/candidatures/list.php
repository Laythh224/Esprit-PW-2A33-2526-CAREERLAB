<?php $title = "Gestion des candidatures"; ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-users me-2"></i>Candidatures reçues</h1>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Poste</th>
                            <th>Candidat</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($candidatures as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?= htmlspecialchars($c['job_title']) ?></td>
                            <td><?= htmlspecialchars($c['nom']) ?></td>
                            <td><?= htmlspecialchars($c['email']) ?></td>
                            <td><?= htmlspecialchars($c['telephone'] ?? '-') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></td>
                            <td>
                                <?php
                                $status_class = [
                                    'en_attente' => 'warning',
                                    'vue' => 'info',
                                    'acceptee' => 'success',
                                    'refusee' => 'danger'
                                ];
                                ?>
                                <span class="badge bg-<?= $status_class[$c['status']] ?>">
                                    <?= $c['status'] ?>
                                </span>
                            </td>
                            <td>
                                <a href="index.php?action=voir_candidature&id=<?= $c['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>