<?php
declare(strict_types=1);
$title = 'Create Post';
ob_start();
?>
<a class="text-muted small" href="index.php?c=post&a=index">&larr; Back</a>
<h2 class="mt-2 mb-3">Create Post</h2>

<form method="post" class="bg-white border rounded p-3">
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input class="form-control" name="title" value="<?= e($values['title']) ?>">
        <?php if (!empty($errors['title'])): ?><div class="text-danger small"><?= e($errors['title']) ?></div><?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label">Body</label>
        <textarea class="form-control" name="body" rows="5"><?= e($values['body']) ?></textarea>
        <?php if (!empty($errors['body'])): ?><div class="text-danger small"><?= e($errors['body']) ?></div><?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label">Photo URL</label>
        <input class="form-control" name="photo_url" value="<?= e($values['photo_url']) ?>">
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Flair</label>
            <select class="form-control" name="flair">
                <?php foreach (['Question','Thread','Disclaimer'] as $f): ?>
                    <option value="<?= e($f) ?>" <?= $values['flair'] === $f ? 'selected' : '' ?>><?= e($f) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['flair'])): ?><div class="text-danger small"><?= e($errors['flair']) ?></div><?php endif; ?>
        </div>
        <div class="col-md-4">
            <label class="form-label">Status</label>
            <select class="form-control" name="status">
                <?php foreach (['active','locked','deleted'] as $s): ?>
                    <option value="<?= e($s) ?>" <?= $values['status'] === $s ? 'selected' : '' ?>><?= e($s) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['status'])): ?><div class="text-danger small"><?= e($errors['status']) ?></div><?php endif; ?>
        </div>
        <div class="col-md-4">
            <label class="form-label">Author Type</label>
            <select class="form-control" name="author_type" id="authorType">
                <?php foreach (['Utilisateur','Formateur','Entreprise'] as $a): ?>
                    <option value="<?= e($a) ?>" <?= $values['author_type'] === $a ? 'selected' : '' ?>><?= e($a) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['author_type'])): ?><div class="text-danger small"><?= e($errors['author_type']) ?></div><?php endif; ?>
        </div>
    </div>

    <div class="mt-3">
        <label class="form-label">Minimum upvotes (post) to be eligible for Challenge selection</label>
        <input class="form-control" name="min_upvotes_for_challenge" value="<?= e($values['min_upvotes_for_challenge']) ?>">
        <?php if (!empty($errors['min_upvotes_for_challenge'])): ?><div class="text-danger small"><?= e($errors['min_upvotes_for_challenge']) ?></div><?php endif; ?>
        <div class="small text-muted mt-1">This value is used only for Formateur/Entreprise posts (server-side).</div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button class="btn btn-success" type="submit">Save</button>
        <a class="btn btn-outline-secondary" href="index.php?c=post&a=index">Cancel</a>
    </div>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/../_layout.php';
?>

