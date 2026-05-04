<?php
declare(strict_types=1);

// Simple admin to add/edit translations for questions and reponses
// Usage: open translations/admin.php in browser. No auth in this demo — secure in production.

$pdo = require dirname(__DIR__) . '/db.php';

$msg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posted = $_POST;
    try {
        $pdo->beginTransaction();

        // questions
        foreach ($posted as $key => $val) {
            if (strpos($key, 'q_') === 0) {
                // format q_{id}_{lang}
                [$_, $qid, $lang] = explode('_', $key, 3) + [null, null, null];
                if (!$qid || !$lang) continue;
                $texte = trim((string)$val);
                if ($texte === '') continue;
                $stmt = $pdo->prepare('INSERT INTO question_translations (id_question, lang, texte) VALUES (:qid, :lang, :texte) ON DUPLICATE KEY UPDATE texte = VALUES(texte)');
                $stmt->execute([':qid' => $qid, ':lang' => $lang, ':texte' => $texte]);
            }
            if (strpos($key, 'r_') === 0) {
                // format r_{id}_{lang}
                [$_, $rid, $lang] = explode('_', $key, 3) + [null, null, null];
                if (!$rid || !$lang) continue;
                $texte = trim((string)$val);
                if ($texte === '') continue;
                $stmt = $pdo->prepare('INSERT INTO reponse_translations (id_reponse, lang, texte) VALUES (:rid, :lang, :texte) ON DUPLICATE KEY UPDATE texte = VALUES(texte)');
                $stmt->execute([':rid' => $rid, ':lang' => $lang, ':texte' => $texte]);
            }
        }

        $pdo->commit();
        $msg = 'Traductions enregistrées.';
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $msg = 'Erreur: ' . $e->getMessage();
    }
}

$questions = [];
$stmt = $pdo->query('SELECT id, texte FROM questions ORDER BY id ASC');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $qid = (int)$row['id'];
    $questions[$qid] = ['default' => $row['texte'], 'reponses' => []];
}

if ($questions) {
    $qids = array_keys($questions);
    $in = implode(',', array_fill(0, count($qids), '?'));
    // fetch responses
    $stmt = $pdo->prepare("SELECT id, id_question, texte FROM reponses WHERE id_question IN ($in) ORDER BY id ASC");
    $stmt->execute($qids);
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rid = (int)$r['id'];
        $qid = (int)$r['id_question'];
        $questions[$qid]['reponses'][$rid] = $r['texte'];
    }

    // fetch translations for questions
    $stmt = $pdo->prepare("SELECT id_question, lang, texte FROM question_translations WHERE id_question IN ($in)");
    $stmt->execute($qids);
    while ($t = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $questions[(int)$t['id_question']]['qt'][$t['lang']] = $t['texte'];
    }

    // fetch translations for reponses
    $repIds = [];
    foreach ($questions as $qid => $data) {
        foreach ($data['reponses'] as $rid => $_) $repIds[] = $rid;
    }
    if ($repIds) {
        $inR = implode(',', array_fill(0, count($repIds), '?'));
        $stmt = $pdo->prepare("SELECT id_reponse, lang, texte FROM reponse_translations WHERE id_reponse IN ($inR)");
        $stmt->execute($repIds);
        while ($t = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $questions[(int)$repDefaultsId = 0]; // no-op to avoid psalm warnings
            $rid = (int)$t['id_reponse'];
            // find parent qid
            foreach ($questions as $qid => &$data) {
                if (array_key_exists($rid, $data['reponses'])) {
                    $data['rt'][$rid][$t['lang']] = $t['texte'];
                    break;
                }
            }
            unset($data);
        }
    }
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin traductions</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <style>textarea{width:100%;min-height:60px}</style>
</head>
<body class="p-4">
    <div class="container">
        <h1>Admin — Traductions (FR/EN/AR)</h1>
        <?php if ($msg !== null): ?><div class="alert alert-info"><?php echo htmlspecialchars($msg, ENT_QUOTES); ?></div><?php endif; ?>
        <form method="post">
            <?php foreach ($questions as $qid => $q): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Q<?php echo $qid; ?> — Texte par défaut</h5>
                        <p><?php echo htmlspecialchars($q['default'], ENT_QUOTES); ?></p>

                        <div class="row">
                            <div class="col-md-4">
                                <label>FR</label>
                                <textarea name="q_<?php echo $qid; ?>_fr"><?php echo htmlspecialchars($q['qt']['fr'] ?? $q['default'], ENT_QUOTES); ?></textarea>
                            </div>
                            <div class="col-md-4">
                                <label>EN</label>
                                <textarea name="q_<?php echo $qid; ?>_en"><?php echo htmlspecialchars($q['qt']['en'] ?? '', ENT_QUOTES); ?></textarea>
                            </div>
                            <div class="col-md-4">
                                <label>AR</label>
                                <textarea name="q_<?php echo $qid; ?>_ar"><?php echo htmlspecialchars($q['qt']['ar'] ?? '', ENT_QUOTES); ?></textarea>
                            </div>
                        </div>

                        <hr>
                        <h6>Réponses</h6>
                        <?php foreach ($q['reponses'] as $rid => $rtext): ?>
                            <div class="mb-2">
                                <strong>R<?php echo $rid; ?></strong>
                                <p><?php echo htmlspecialchars($rtext, ENT_QUOTES); ?></p>
                                <div class="row">
                                    <div class="col-md-4"><label>FR</label><textarea name="r_<?php echo $rid; ?>_fr"><?php echo htmlspecialchars($q['rt'][$rid]['fr'] ?? $rtext, ENT_QUOTES); ?></textarea></div>
                                    <div class="col-md-4"><label>EN</label><textarea name="r_<?php echo $rid; ?>_en"><?php echo htmlspecialchars($q['rt'][$rid]['en'] ?? '', ENT_QUOTES); ?></textarea></div>
                                    <div class="col-md-4"><label>AR</label><textarea name="r_<?php echo $rid; ?>_ar"><?php echo htmlspecialchars($q['rt'][$rid]['ar'] ?? '', ENT_QUOTES); ?></textarea></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <button class="btn btn-primary" type="submit">Enregistrer les traductions</button>
        </form>
    </div>
</body>
</html>
