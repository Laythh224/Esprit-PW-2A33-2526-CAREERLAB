<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

// Debug: Log incoming requests
error_log('[Translations] Request received: ' . json_encode($_GET));

$lang = $_GET['lang'] ?? 'fr';
$ids = $_GET['ids'] ?? '';

// Test response for debugging
if (isset($_GET['test'])) {
    echo json_encode([
        'status' => 'working',
        'lang' => $lang,
        'ids' => $ids,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$lang = in_array($lang, ['fr', 'en', 'ar'], true) ? $lang : 'fr';

if ($ids === '') {
    echo json_encode(['error' => 'No ids provided'], JSON_UNESCAPED_UNICODE);
    exit;
}

$idList = array_values(array_filter(array_map('intval', explode(',', $ids))));
if ($idList === []) {
    echo json_encode(['error' => 'Invalid ids'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Translation content for existing database questions/reponses.
// The test page reads the question and response IDs from evaluation_db,
// then this file applies the corresponding EN/AR text.
$translationMap = [
    2 => [
        'en' => 'What is the main organ affected by pneumonia?',
        'ar' => 'ما هو العضو الرئيسي الذي تصيبه ذات الرئة؟',
        'answers' => [
            3 => ['en' => 'The lungs', 'ar' => 'الرئتان'],
            4 => ['en' => 'The heart', 'ar' => 'القلب'],
            5 => ['en' => 'The liver', 'ar' => 'الكبد'],
        ],
    ],
    3 => [
        'en' => 'Which disease is caused by a virus?',
        'ar' => 'ما المرض الذي يسببه فيروس؟',
        'answers' => [
            6 => ['en' => 'Diabetes', 'ar' => 'داء السكري'],
            7 => ['en' => 'Influenza', 'ar' => 'الإنفلونزا'],
            8 => ['en' => 'Osteoarthritis', 'ar' => 'الفصال العظمي'],
        ],
    ],
    4 => [
        'en' => 'Which part stores electrical energy in a car?',
        'ar' => 'ما الجزء الذي يخزن الطاقة الكهربائية في السيارة؟',
        'answers' => [
            9 => ['en' => 'Battery', 'ar' => 'بطارية'],
            10 => ['en' => 'Alternator', 'ar' => 'مولد كهربائي'],
            11 => ['en' => 'Oil filter', 'ar' => 'فلتر الزيت'],
        ],
    ],
    5 => [
        'en' => 'What is the role of engine oil?',
        'ar' => 'ما دور زيت المحرك؟',
        'answers' => [
            12 => ['en' => 'To increase the speed of the wheels', 'ar' => 'زيادة سرعة العجلات'],
            13 => ['en' => 'To cool and lubricate the engine', 'ar' => 'تبريد المحرك وتزييته'],
            14 => ['en' => 'To clean the windows', 'ar' => 'تنظيف النوافذ'],
        ],
    ],
    6 => [
        'en' => 'What does the braking system allow?',
        'ar' => 'ماذا يسمح نظام الفرامل؟',
        'answers' => [
            15 => ['en' => 'To accelerate the car', 'ar' => 'تسريع السيارة'],
            16 => ['en' => 'To increase fuel consumption', 'ar' => 'زيادة استهلاك الوقود'],
            17 => ['en' => 'To stop or slow down the vehicle', 'ar' => 'إيقاف السيارة أو إبطاؤها'],
        ],
    ],
    7 => [
        'en' => 'What does HTML stand for?',
        'ar' => 'ماذا تعني HTML؟',
        'answers' => [
            18 => ['en' => 'Server-side programming language', 'ar' => 'لغة برمجة من جهة الخادم'],
            19 => ['en' => 'A language for structuring web pages', 'ar' => 'لغة لتنظيم صفحات الويب'],
            20 => ['en' => 'An operating system', 'ar' => 'نظام تشغيل'],
        ],
    ],
    8 => [
        'en' => 'What is a database used for?',
        'ar' => 'ما فائدة قاعدة البيانات؟',
        'answers' => [
            21 => ['en' => 'Store data', 'ar' => 'تخزين البيانات'],
            22 => ['en' => 'Draw interfaces', 'ar' => 'رسم الواجهات'],
            23 => ['en' => 'Compile code', 'ar' => 'ترجمة الشفرة البرمجية'],
        ],
    ],
    9 => [
        'en' => 'What is the role of a framework?',
        'ar' => 'ما هو دور إطار العمل؟',
        'answers' => [
            24 => ['en' => 'Replace the computer', 'ar' => 'استبدال الحاسوب'],
            25 => ['en' => 'Facilitate application development', 'ar' => 'تسهيل تطوير التطبيقات'],
            26 => ['en' => 'Turn off the system', 'ar' => 'إيقاف النظام'],
        ],
    ],
    10 => [
        'en' => 'A general practitioner sees blood pressure of 160/100 mmHg. What does this indicate?',
        'ar' => 'يلاحظ طبيب عام ضغط دم 160/100 ملم زئبق. ماذا يدل ذلك؟',
        'answers' => [
            27 => ['en' => 'Hypertension', 'ar' => 'ارتفاع ضغط الدم'],
            28 => ['en' => 'Hypotension', 'ar' => 'انخفاض ضغط الدم'],
            29 => ['en' => 'A normal value', 'ar' => 'قيمة طبيعية'],
        ],
    ],
    14 => [
        'en' => 'Which organ produces insulin?',
        'ar' => 'ما العضو الذي ينتج الأنسولين؟',
        'answers' => [
            33 => ['en' => 'The pancreas', 'ar' => 'البنكرياس'],
            34 => ['en' => 'The liver', 'ar' => 'الكبد'],
            35 => ['en' => 'The kidneys', 'ar' => 'الكليتان'],
        ],
    ],
    15 => [
        'en' => 'What is the main role of white blood cells?',
        'ar' => 'ما هو الدور الرئيسي لخلايا الدم البيضاء؟',
        'answers' => [
            36 => ['en' => 'Transport oxygen', 'ar' => 'نقل الأكسجين'],
            37 => ['en' => 'Fight infections', 'ar' => 'مكافحة العدوى'],
            38 => ['en' => 'Transport nutrients', 'ar' => 'نقل العناصر الغذائية'],
        ],
    ],
];

try {
    $pdo = require dirname(__DIR__) . '/db.php';

    if (!$pdo instanceof PDO) {
        throw new RuntimeException('DB connection not available');
    }

    $placeholders = implode(',', array_fill(0, count($idList), '?'));
    $stmtQ = $pdo->prepare("SELECT id, texte FROM questions WHERE id IN ($placeholders)");
    $stmtQ->execute($idList);

    $questions = [];
    while ($row = $stmtQ->fetch(PDO::FETCH_ASSOC)) {
        $questions[(int) $row['id']] = $row['texte'];
    }

    $stmtR = $pdo->prepare("SELECT id, id_question, texte FROM reponses WHERE id_question IN ($placeholders) ORDER BY id ASC");
    $stmtR->execute($idList);

    $answersByQuestion = [];
    while ($row = $stmtR->fetch(PDO::FETCH_ASSOC)) {
        $qid = (int) $row['id_question'];
        $rid = (int) $row['id'];
        $answersByQuestion[$qid][$rid] = $row['texte'];
    }

    $outQuestions = [];
    $outAnswers = [];

    foreach ($idList as $qid) {
        if (!isset($questions[$qid])) {
            continue;
        }

        $outQuestions[$qid] = $questions[$qid];
        if ($lang !== 'fr' && isset($translationMap[$qid][$lang])) {
            $outQuestions[$qid] = $translationMap[$qid][$lang];
        }

        $outAnswers[$qid] = [];
        foreach (($answersByQuestion[$qid] ?? []) as $rid => $defaultAnswer) {
            $translatedAnswer = $defaultAnswer;
            if ($lang !== 'fr' && isset($translationMap[$qid]['answers'][$rid][$lang])) {
                $translatedAnswer = $translationMap[$qid]['answers'][$rid][$lang];
            }
            $outAnswers[$qid][$rid] = $translatedAnswer;
        }
    }

    echo json_encode([
        'questions' => $outQuestions,
        'reponses' => $outAnswers,
        'lang' => $lang,
        'dir' => $lang === 'ar' ? 'rtl' : 'ltr',
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
