<h1><?= htmlspecialchars($title ?? 'Erreur', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h1>
<p><?= htmlspecialchars($message ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>

