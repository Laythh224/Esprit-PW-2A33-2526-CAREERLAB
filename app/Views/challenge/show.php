<?php
declare(strict_types=1);
$title = $challenge ? ('Challenge #' . (int)$challenge['id']) : 'Challenge not found';
ob_start();
?>
<?php if (!$challenge): ?>
    <div class="alert alert-danger">Challenge not found.</div>
<?php else: ?>
    <a class="text-muted small" href="index.php?c=challenge&a=index">&larr; Back</a>
    <h2 class="mt-2 mb-2"><?= e($challenge['theme']) ?></h2>
    <div class="d-flex flex-wrap gap-2 mb-2">
        <span class="badge bg-primary"><?= e($challenge['flair']) ?></span>
        <span class="badge bg-secondary"><?= e($challenge['creator_type']) ?></span>
        <?php if ($challenge['status'] === 'upcoming'): ?>
            <span class="badge bg-warning text-dark">upcoming</span>
        <?php elseif ($challenge['status'] === 'active'): ?>
            <span class="badge bg-success">active</span>
        <?php else: ?>
            <span class="badge bg-dark"><?= e($challenge['status']) ?></span>
        <?php endif; ?>
    </div>
    <div class="small text-muted mb-3">
        <?= e($challenge['start_date']) ?> → <?= e($challenge['end_date']) ?>
        · post: <?= e($challenge['post_title']) ?>
        · post upvotes: <?= (int)$challenge['post_upvotes'] ?> / <?= (int)$challenge['post_min_for_challenge'] ?>
    </div>
    <div class="bg-white border rounded p-3 mb-3">
        <p class="mb-0"><?= nl2br(e($challenge['description'])) ?></p>
    </div>
    <div class="bg-white border rounded p-3">
        <div class="small text-muted text-uppercase mb-1">Reward</div>
        <div class="fw-semibold mb-1"><?= e($challenge['reward_title']) ?></div>
        <div><?= nl2br(e($challenge['reward_description'])) ?></div>
    </div>

    <?php if ($challenge['status'] === 'upcoming'): ?>
        <div class="alert alert-warning mt-3 mb-0">
            This challenge is <b>upcoming</b>. Comments are not eligible until the attached post reaches the minimum upvotes.
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../_layout.php';
?>

