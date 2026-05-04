<?php
// Demo translation page (standalone)
declare(strict_types=1);

$questions = [
    [
        "question" => [
            "fr" => "Quel est ton métier préféré ?",
            "en" => "What is your favorite job?",
            "ar" => "ما هي مهنتك المفضلة؟"
        ],
        "reponses" => [
            ["fr"=>"Médecin","en"=>"Doctor","ar"=>"طبيب"],
            ["fr"=>"Ingénieur","en"=>"Engineer","ar"=>"مهندس"],
            ["fr"=>"Professeur","en"=>"Teacher","ar"=>"أستاذ"]
        ]
    ]
];

$lang = $_GET['lang'] ?? ($_COOKIE['lang'] ?? 'fr');
$dir = $lang === 'ar' ? 'rtl' : 'ltr';

?>
<!doctype html>
<html lang="<?php echo htmlspecialchars($lang, ENT_QUOTES); ?>" dir="<?php echo $dir; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Démo Traduction</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;padding:20px}
        .lang-btn{margin-right:8px;padding:8px 12px;cursor:pointer}
        .card{border:1px solid #ddd;padding:16px;border-radius:6px;max-width:600px}
        .fade{transition:opacity 250ms ease,transform 250ms ease}
        .fade-hidden{opacity:0;transform:translateY(8px)}
    </style>
</head>
<body>
    <h2>Démo traduction (FR / EN / AR)</h2>
    <div>
        <button class="lang-btn" data-lang="fr">FR</button>
        <button class="lang-btn" data-lang="en">EN</button>
        <button class="lang-btn" data-lang="ar">AR</button>
    </div>

    <div style="height:12px"></div>

    <div id="question-card" class="card fade">
        <?php $q = $questions[0]; ?>
        <h3 id="question-text"><?php echo htmlspecialchars($q['question'][$lang], ENT_QUOTES); ?></h3>
        <ul id="answers-list">
            <?php foreach ($q['reponses'] as $r): ?>
                <li class="answer-item"><?php echo htmlspecialchars($r[$lang], ENT_QUOTES); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script>
        // expose endpoint path
        const GET_QUESTION_ENDPOINT = 'get_question.php';
    </script>
    <script src="js/script.js"></script>
</body>
</html>
