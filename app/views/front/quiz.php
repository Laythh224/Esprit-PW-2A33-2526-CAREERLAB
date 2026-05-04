<?php

declare(strict_types=1);

$idMetier = (int) ($idMetier ?? 0);
$questions = is_array($questions ?? null) ? $questions : [];
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
    <title>Quiz metier <?php echo $idMetier; ?> - Career Lab</title>

    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        .quiz-question-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(9, 30, 62, 0.12);
        }
    </style>
</head>
<body>
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="index.php?route=team" class="navbar-brand p-0">
                <img src="img/career_lab.png" alt="Career Lab" style="height: 44px; width: auto; background: #ffffff; padding: 4px 8px; border-radius: 8px;">
            </a>
            <div class="navbar-nav ms-auto py-0">
                <a href="index.php?route=team" class="nav-item nav-link">Choix metier</a>
            </div>
        </nav>

        <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 60px;">
            <div class="row py-5">
                <div class="col-12 text-center">
                    <h1 class="display-6 text-white">Quiz du metier <?php echo $idMetier; ?></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="container" style="max-width: 980px;">
            <?php if ($dbError !== null): ?>
                <div class="alert alert-danger" role="alert"><?php echo $e($dbError); ?></div>
            <?php endif; ?>

            <?php if ($validationError !== null): ?>
                <div class="alert alert-warning" role="alert"><?php echo $e($validationError); ?></div>
            <?php endif; ?>

            <?php if ($questions === []): ?>
                <div class="alert alert-info" role="alert">Aucune question trouvee pour ce metier.</div>
            <?php else: ?>
                <form method="post" action="index.php?route=team/submit" onsubmit="return validateQuizForm(<?php echo count($questions); ?>);" novalidate>
                    <input type="hidden" name="id_metier" value="<?php echo $idMetier; ?>">

                    <!-- Language switcher -->
                    <div class="mb-3">
                        <div class="btn-group" role="group" aria-label="Lang switch">
                            <button type="button" class="btn btn-outline-secondary lang-switch" data-lang="fr">FR</button>
                            <button type="button" class="btn btn-outline-secondary lang-switch" data-lang="en">EN</button>
                            <button type="button" class="btn btn-outline-secondary lang-switch" data-lang="ar">AR</button>
                            <button type="button" class="btn btn-outline-info test-endpoint">Test API</button>
                        </div>
                    </div>

                    <?php foreach ($questions as $index => $pack): ?>
                        <?php
                            $question = $pack['question'];
                            $reponses = $pack['reponses'];
                            $questionId = (int) ($question->getId() ?? 0);
                        ?>
                        <section class="card quiz-question-card mb-3 question-card" data-question-id="<?php echo $questionId; ?>">
                            <div class="card-body p-4">
                                <h2 class="h5 mb-3"><?php echo ((int) $index + 1) . '. ' . $e($question->getTexte()); ?></h2>

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
                                            <?php echo $e($reponse->getTexte()); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endforeach; ?>

                    <button class="btn btn-success btn-lg w-100" type="submit">Valider mes reponses</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/validation.js"></script>
    <script>
        // Language switcher: robust fetch translations and apply to DOM
        document.addEventListener('DOMContentLoaded', function () {
            var switchButtons = document.querySelectorAll('.lang-switch');
            var form = document.querySelector('form');
            if (!form) return;

            function getQuestionIds(){
                var secs = Array.from(document.querySelectorAll('[data-question-id]'));
                console.log('[Debug] Found sections with data-question-id:', secs.length);
                var ids = secs.map(function(s){ 
                    var id = s.getAttribute('data-question-id');
                    console.log('[Debug] Section ID:', id, 'Section:', s);
                    return id; 
                }).filter(Boolean);
                console.log('[Debug] Final question IDs:', ids);
                return ids;
            }

            async function fetchTranslations(lang){
                var ids = getQuestionIds();
                if (ids.length === 0) return;

                // build URL - try multiple approaches
                var baseUrl = window.location.origin;
                var url = baseUrl + '/translations/get_translations.php?lang=' + lang + '&ids=' + ids.join(',');
                console.log('[i18n] Testing URL:', url);

                try{
                    console.log('[i18n] fetching translations', url.toString());
                    console.log('[i18n] question IDs:', ids);
                    console.log('[i18n] target language:', lang);
                    
                    var res = await fetch(url.toString(), { credentials: 'same-origin' });
                    if (!res.ok) {
                        var txt = await res.text();
                        console.error('[i18n] HTTP error:', res.status, txt);
                        throw new Error('Network error: ' + res.status + ' ' + txt);
                    }
                    var data = await res.json();
                    console.log('[i18n] response data:', data);
                    if (data.error) {
                        console.error('[i18n] API error:', data.error);
                        throw new Error(data.error);
                    }

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

            // Test endpoint button
            var testBtn = document.querySelector('.test-endpoint');
            if (testBtn) {
                testBtn.addEventListener('click', function(){
                    var testUrl = window.location.origin + '/translations/get_translations.php?test=1&lang=en&ids=2,3,4';
                    console.log('[Test] Testing endpoint:', testUrl);
                    fetch(testUrl)
                        .then(function(res){ return res.json(); })
                        .then(function(data){ 
                            console.log('[Test] Endpoint response:', data);
                            alert('Endpoint test: ' + JSON.stringify(data, null, 2));
                        })
                        .catch(function(err){ 
                            console.error('[Test] Endpoint error:', err);
                            alert('Endpoint error: ' + err.message);
                        });
                });
            }

            // apply saved language on load
            var saved = localStorage.getItem('lang');
            if (saved) fetchTranslations(saved);
        });
    </script>
</body>
</html>
