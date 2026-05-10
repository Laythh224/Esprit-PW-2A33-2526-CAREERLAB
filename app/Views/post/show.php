<?php
declare(strict_types=1);
$title = $post ? ('Post #' . (int)$post['id']) : 'Post not found';
ob_start();
?>
<?php if (!$post): ?>
    <div class="alert alert-danger">Post not found.</div>
<?php else: ?>
    <a class="text-muted small" href="index.php?c=post&a=index">&larr; Back</a>
    <h2 class="mt-2 mb-2"><?= e($post['title']) ?></h2>
    <div class="small text-muted mb-3">
        <?= e($post['author_type']) ?> · <?= e($post['flair']) ?> · <?= e($post['created_at']) ?>
        · upvotes: <?= (int)$post['upvote_count'] ?>
        · min for challenge: <?= (int)$post['min_upvotes_for_challenge'] ?>
    </div>
    <?php if (!empty($post['photo_url'])): ?>
        <img src="<?= e($post['photo_url']) ?>" alt="" class="img-fluid rounded mb-3" style="max-height:320px;object-fit:cover;">
    <?php endif; ?>
    <div class="bg-white border rounded p-3">
        <p class="mb-0"><?= nl2br(e($post['body'])) ?></p>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../_layout.php';
?>

