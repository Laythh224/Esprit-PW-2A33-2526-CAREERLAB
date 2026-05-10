<?php
declare(strict_types=1);
$title = 'Create Challenge';
ob_start();
?>
<a class="text-muted small" href="index.php?c=challenge&a=index">&larr; Back</a>
<h2 class="mt-2 mb-3">Create Challenge</h2>

<form method="post" class="bg-white border rounded p-3">
    <div class="mb-3">
        <label class="form-label">Attach to Post</label>
        <select class="form-control" name="post_id">
            <option value="">Select a post</option>
            <?php foreach ($posts as $p): ?>
                <?php
                $up = (int)$p['upvote_count'];
                $min = (int)$p['min_upvotes_for_challenge'];
                $eligible = $up >= $min;
                $label = '#' . (int)$p['id'] . ' — ' . $p['title'] . ' (upvotes: ' . $up . ', min: ' . $min . ')' . ($eligible ? '' : ' — upcoming');
                ?>
                <option value="<?= (int)$p['id'] ?>" <?= $values['post_id'] === (string)$p['id'] ? 'selected' : '' ?>>
                    <?= e($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['post_id'])): ?><div class="text-danger small"><?= e($errors['post_id']) ?></div><?php endif; ?>
        <div class="small text-muted mt-1">Status will be saved as <b>upcoming</b> until the post becomes eligible.</div>
    </div>

    <div class="mb-3">
        <label class="form-label">Theme</label>
        <input class="form-control" name="theme" value="<?= e($values['theme']) ?>">
        <?php if (!empty($errors['theme'])): ?><div class="text-danger small"><?= e($errors['theme']) ?></div><?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description" rows="4"><?= e($values['description']) ?></textarea>
        <?php if (!empty($errors['description'])): ?><div class="text-danger small"><?= e($errors['description']) ?></div><?php endif; ?>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Flair</label>
            <select class="form-control" name="flair">
                <?php foreach (['Projet','Showcast','Débat','Pitch'] as $f): ?>
                    <option value="<?= e($f) ?>" <?= $values['flair'] === $f ? 'selected' : '' ?>><?= e($f) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['flair'])): ?><div class="text-danger small"><?= e($errors['flair']) ?></div><?php endif; ?>
        </div>
        <div class="col-md-4">
            <label class="form-label">Creator Type</label>
            <select class="form-control" name="creator_type">
                <?php foreach (['Formateur','Entreprise'] as $t): ?>
                    <option value="<?= e($t) ?>" <?= $values['creator_type'] === $t ? 'selected' : '' ?>><?= e($t) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['creator_type'])): ?><div class="text-danger small"><?= e($errors['creator_type']) ?></div><?php endif; ?>
        </div>
        <div class="col-md-4">
            <label class="form-label">Reward Type</label>
            <select class="form-control" name="reward_type">
                <?php foreach (['job','course'] as $rt): ?>
                    <option value="<?= e($rt) ?>" <?= $values['reward_type'] === $rt ? 'selected' : '' ?>><?= e($rt) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['reward_type'])): ?><div class="text-danger small"><?= e($errors['reward_type']) ?></div><?php endif; ?>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <label class="form-label">Start Date</label>
            <input class="form-control" name="start_date" value="<?= e($values['start_date']) ?>">
            <?php if (!empty($errors['start_date'])): ?><div class="text-danger small"><?= e($errors['start_date']) ?></div><?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label">End Date</label>
            <input class="form-control" name="end_date" value="<?= e($values['end_date']) ?>">
            <?php if (!empty($errors['end_date'])): ?><div class="text-danger small"><?= e($errors['end_date']) ?></div><?php endif; ?>
        </div>
    </div>

    <div class="mt-3">
        <label class="form-label">Reward Title</label>
        <input class="form-control" name="reward_title" value="<?= e($values['reward_title']) ?>">
        <?php if (!empty($errors['reward_title'])): ?><div class="text-danger small"><?= e($errors['reward_title']) ?></div><?php endif; ?>
    </div>
    <div class="mt-3">
        <label class="form-label">Reward Description</label>
        <textarea class="form-control" name="reward_description" rows="3"><?= e($values['reward_description']) ?></textarea>
        <?php if (!empty($errors['reward_description'])): ?><div class="text-danger small"><?= e($errors['reward_description']) ?></div><?php endif; ?>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button class="btn btn-success" type="submit">Save</button>
        <a class="btn btn-outline-secondary" href="index.php?c=challenge&a=index">Cancel</a>
    </div>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/../_layout.php';
?>

