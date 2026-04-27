<?php
declare(strict_types=1);
$title = 'Challenges';
ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="m-0">Challenges</h2>
    <a class="btn btn-sm btn-success" href="index.php?c=challenge&a=create">Create Challenge</a>
</div>

<form method="get" class="mb-3">
    <input type="hidden" name="c" value="challenge">
    <input type="hidden" name="a" value="index">
    <div class="input-group">
        <input class="form-control" name="q" value="<?= e((string)($_GET['q'] ?? '')) ?>" placeholder="Search...">
        <button class="btn btn-primary" type="submit">Search</button>
    </div>
</form>

<?php if (!$challenges): ?>
    <div class="alert alert-info">No challenges yet.</div>
<?php else: ?>
    <div class="list-group">
        <?php foreach ($challenges as $c): ?>
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div class="flex-grow-1">
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <span class="badge bg-primary"><?= e($c['flair']) ?></span>
                            <span class="badge bg-secondary"><?= e($c['creator_type']) ?></span>
                            <?php if ($c['status'] === 'upcoming'): ?>
                                <span class="badge bg-warning text-dark">upcoming</span>
                            <?php elseif ($c['status'] === 'active'): ?>
                                <span class="badge bg-success">active</span>
                            <?php else: ?>
                                <span class="badge bg-dark"><?= e($c['status']) ?></span>
                            <?php endif; ?>
                        </div>
                        <a class="h5 d-block mb-1 text-decoration-none" href="index.php?c=challenge&a=show&id=<?= (int)$c['id'] ?>">
                            <?= e($c['theme']) ?>
                        </a>
                        <div class="small text-muted">
                            <?= e($c['start_date']) ?> → <?= e($c['end_date']) ?>
                            · post: <?= e($c['post_title']) ?>
                            · post upvotes: <?= (int)$c['post_upvotes'] ?> / <?= (int)$c['post_min_for_challenge'] ?>
                        </div>
                    </div>
                    <a class="btn btn-sm btn-outline-secondary" href="index.php?c=challenge&a=show&id=<?= (int)$c['id'] ?>">Open</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../_layout.php';
?>

