<?php
declare(strict_types=1);
$title = 'Posts';
ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="m-0">Posts</h2>
    <a class="btn btn-sm btn-success" href="index.php?c=post&a=create">Create Post</a>
</div>

<form method="get" class="mb-3">
    <input type="hidden" name="c" value="post">
    <input type="hidden" name="a" value="index">
    <div class="input-group">
        <input class="form-control" name="q" value="<?= e((string)($_GET['q'] ?? '')) ?>" placeholder="Search...">
        <button class="btn btn-primary" type="submit">Search</button>
    </div>
</form>

<?php if (!$posts): ?>
    <div class="alert alert-info">No posts yet.</div>
<?php else: ?>
    <div class="list-group">
        <?php foreach ($posts as $p): ?>
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div class="flex-grow-1">
                        <div class="small text-muted mb-1">
                            <?= e($p['author_type']) ?> · <?= e($p['flair']) ?> · <?= e($p['created_at']) ?>
                        </div>
                        <a class="h5 d-block mb-2 text-decoration-none" href="index.php?c=post&a=show&id=<?= (int)$p['id'] ?>">
                            <?= e($p['title']) ?>
                        </a>
                        <div class="small text-muted">
                            upvotes: <?= (int)$p['upvote_count'] ?>
                            <?php if ((int)$p['min_upvotes_for_challenge'] > 0): ?>
                                · min for challenge: <?= (int)$p['min_upvotes_for_challenge'] ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <form method="post" action="index.php?c=post&a=upvote" class="m-0">
                        <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                        <button class="btn btn-sm btn-outline-primary" type="submit">Upvote</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../_layout.php';
?>

