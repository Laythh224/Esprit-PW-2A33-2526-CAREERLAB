<?php

declare(strict_types=1);

$testToken = isset($testToken) ? (string) $testToken : '';
$tests = is_array($tests ?? null) ? $tests : [];
$dbError = isset($dbError) ? (string) $dbError : null;
$validationError = isset($validationError) ? (string) $validationError : null;
$selectedAnswers = is_array($selectedAnswers ?? null) ? $selectedAnswers : [];

$e = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Passer le test - Career Lab</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .test-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(9, 30, 62, 0.12);
        }
    </style>
</head>
<body>
    <div class="container-fluid bg-primary py-5 bg-header">
        <div class="row py-5">
            <div class="col-12 text-center">
                <img src="img/career_lab.png" alt="Career Lab" style="height: 46px; width: auto; background: #ffffff; padding: 4px 8px; border-radius: 8px; margin-bottom: 14px;">
                <h1 class="display-6 text-white">Votre test est prêt</h1>
            </div>
        </div>
    </div>

    <div class="container py-5" style="max-width: 960px;">
        <div class="card test-card">
            <div class="card-body p-4 p-md-5">
                <?php if ($dbError !== null): ?>
                    <div class="alert alert-danger" role="alert"><?php echo $e($dbError); ?></div>
                <?php endif; ?>

                <?php if ($validationError !== null): ?>
                    <div class="alert alert-warning" role="alert"><?php echo $e($validationError); ?></div>
                <?php endif; ?>

                <?php if ($tests === []): ?>
                    <div class="alert alert-info" role="alert">Aucune question disponible pour ce test.</div>
                <?php else: ?>
                    <form id="testForm" method="post" action="index.php?route=team/test/submit" novalidate>
                        <input type="hidden" name="test_token" value="<?php echo $e($testToken); ?>">

                        <!-- Language switcher -->
                        <div class="mb-3">
                            <div class="btn-group" role="group" aria-label="Lang switch">
                                <button type="button" class="btn btn-outline-secondary lang-switch" data-lang="fr">FR</button>
                                <button type="button" class="btn btn-outline-secondary lang-switch" data-lang="en">EN</button>
                                <button type="button" class="btn btn-outline-secondary lang-switch" data-lang="ar">AR</button>
                            </div>
                        </div>

                        <?php foreach ($tests as $index => $pack): ?>
                            <?php
                                $question = $pack['question'];
                                $reponses = is_array($pack['reponses'] ?? null) ? $pack['reponses'] : [];
                                $questionId = (int) ($question->getId() ?? 0);
                                $questionText = (string) $question->getTexte();
                            ?>
                            <section class="card quiz-question-card mb-3 question-card border-0 shadow-sm rounded-3" data-question-id="<?php echo $questionId; ?>">
                                <div class="card-body p-4">
                                    <h2 class="h5 mb-3"><?php echo ((int) $index + 1) . '. ' . $e($questionText); ?></h2>

                                    <?php foreach ($reponses as $reponse): ?>
                                        <?php
                                            $reponseId = (int) ($reponse->getId() ?? 0);
                                            $radioId = 'q' . $questionId . '_r' . $reponseId;
                                            $isChecked = isset($selectedAnswers[$questionId]) && (int) $selectedAnswers[$questionId] === $reponseId;
                                        ?>
                                        <div class="form-check mb-2" data-answer-id="<?php echo $reponseId; ?>">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                id="<?php echo $radioId; ?>"
                                                name="answers[<?php echo $questionId; ?>]"
                                                value="<?php echo $reponseId; ?>"
                                                <?php echo $isChecked ? 'checked' : ''; ?>
                                                required
                                            >
                                            <label class="form-check-label" for="<?php echo $radioId; ?>">
                                                <?php echo $e((string) $reponse->getTexte()); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endforeach; ?>

                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-success btn-lg" type="submit">Valider mes réponses</button>
                            <a class="btn btn-outline-secondary btn-lg" href="index.php?route=team">Retour</a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Result Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Résultat du test</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="resultContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="js/core/bootstrap.min.js"></script>
    <script>
        // Language switcher: robust fetch translations and apply to DOM
        document.addEventListener('DOMContentLoaded', function () {
            var switchButtons = document.querySelectorAll('.lang-switch');
            var form = document.getElementById('testForm');
            if (!form) return;

            function getQuestionIds(){
                var secs = Array.from(document.querySelectorAll('[data-question-id]'));
                return secs.map(function(s){ return s.getAttribute('data-question-id'); }).filter(Boolean);
            }

            async function fetchTranslations(lang){
                var ids = getQuestionIds();
                if (ids.length === 0) return;

                // build URL relative to current document path to avoid base path issues
                var base = window.location.origin + window.location.pathname;
                var url = new URL('translations/get_translations.php', base);
                url.searchParams.set('lang', lang);
                url.searchParams.set('ids', ids.join(','));

                try{
                    console.log('[i18n] fetching translations', url.toString());
                    var res = await fetch(url.toString(), { credentials: 'same-origin' });
                    if (!res.ok) {
                        var txt = await res.text();
                        throw new Error('Network error: ' + res.status + ' ' + txt);
                    }
                    var data = await res.json();
                    if (data.error) throw new Error(data.error);

                    // apply direction and lang
                    if (data.dir) document.documentElement.dir = data.dir;
                    if (data.lang) document.documentElement.lang = data.lang;

                    var sections = Array.from(document.querySelectorAll('[data-question-id]'));
                    sections.forEach(function(section, idx){
                        var qid = section.getAttribute('data-question-id');
                        var qText = (data.questions && data.questions[qid]) ? data.questions[qid] : null;
                        var h = section.querySelector('h2');
                        if (h && qText !== null) h.innerText = (idx + 1) + '. ' + qText;

                        // answers for this question
                        var answersMap = (data.reponses && data.reponses[qid]) ? data.reponses[qid] : {};
                        var items = section.querySelectorAll('[data-answer-id]');
                        items.forEach(function(item){
                            var rid = item.getAttribute('data-answer-id');
                            var label = item.querySelector('label');
                            if (label && answersMap && answersMap[rid]) {
                                label.innerText = answersMap[rid];
                            }
                        });
                    });

                    localStorage.setItem('lang', data.lang || lang);
                    console.log('[i18n] translations applied for lang=', data.lang || lang);
                }catch(err){
                    console.error('[i18n] Translation fetch failed', err);
                }
            }

            switchButtons.forEach(function(b){
                b.addEventListener('click', function(){
                    var lang = b.getAttribute('data-lang');
                    fetchTranslations(lang);
                });
            });

            // apply saved language on load
            var saved = localStorage.getItem('lang');
            if (saved) fetchTranslations(saved);
        });

        (function () {
            var form = document.getElementById('testForm');
            if (!form) return;

            form.addEventListener('submit', function (ev) {
                ev.preventDefault();
                var btn = form.querySelector('button[type=submit]');
                if (btn) btn.disabled = true;

                var fd = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(function (resp) { return resp.json().then(function(data){ if (!resp.ok) { throw new Error(data.error || 'Erreur serveur'); } return data; }); })
                .then(function (data) {
                    var total = data.total || 0;
                    var score = data.score || 0;
                    var details = data.details || [];
                    var passed = score >= Math.ceil(total * 0.5);
                    var resultEl = document.getElementById('resultContent');
                    var html = '';
                    html += '<p class="h4 ' + (passed ? 'text-success' : 'text-danger') + '">' + score + ' / ' + total + '</p>';
                    html += '<p>' + (passed ? 'Bravo, vous avez réussi 🎉' : 'Échec, veuillez réviser 📚') + '</p>';
                    
                    if (details.length > 0) {
                        html += '<hr><h6>Détail des réponses :</h6>';
                        for (var i = 0; i < details.length; i++) {
                            var d = details[i];
                            var statusClass = d.isCorrect ? 'text-success' : 'text-danger';
                            var borderStyle = d.isCorrect ? 'border: 1px solid #28a745; background-color: #f0f8f3;' : 'border: 1px solid #dc3545; background-color: #fdf8f8;';
                            var statusIcon = d.isCorrect ? '✓' : '✗';
                            html += '<div class="mb-3 p-2 rounded" style="' + borderStyle + '">';
                            html += '<p class="mb-1"><strong>Q' + d.number + '. ' + escapeHtml(d.question) + '</strong></p>';
                            html += '<p class="mb-1 small"><strong>Votre réponse :</strong> ' + escapeHtml(d.selectedAnswer) + '</p>';
                            html += '<p class="mb-1 small"><strong>Bonne réponse :</strong> ' + escapeHtml(d.correctAnswer) + '</p>';
                            html += '<p class="mb-0"><strong class="' + statusClass + '">' + statusIcon + ' ' + (d.isCorrect ? 'Correct' : 'Incorrect') + '</strong></p>';
                            html += '</div>';
                        }
                    }
                    resultEl.innerHTML = html;

                    var modalEl = document.getElementById('resultModal');
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }).catch(function (err) {
                    var resultEl = document.getElementById('resultContent');
                    resultEl.innerHTML = '<p class="text-danger">' + (err && err.message ? err.message : 'Une erreur est survenue lors de l envoi.') + '</p>';
                    var modalEl = document.getElementById('resultModal');
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }).finally(function () {
                    if (btn) btn.disabled = false;
                });
            });
        })();
        
        function escapeHtml(text) {
            var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '\"': '&quot;', \"'\": '&#039;' };
            return text.replace(/[&<>\"']/g, function(m) { return map[m]; });
        }
    </script>

</body>
</html>
