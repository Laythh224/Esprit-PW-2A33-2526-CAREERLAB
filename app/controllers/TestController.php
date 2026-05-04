<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Question;
use App\Models\Reponse;
use App\Models\Test;
use App\Models\TestResult;
use App\Utils\TokenGenerator;
use PDO;
use PDOException;

final class TestController extends Controller
{
    public function create(): void
    {
        $errors = [];
        $date = '';
        $heureDebut = '';
        $heureFin = '';
        $idMetierInput = '';
        $dbError = null;

        if ($this->requestMethod() === 'POST') {
            $date = trim((string) ($_POST['date'] ?? ''));
            $heureDebut = trim((string) ($_POST['heure_debut'] ?? ''));
            $heureFin = trim((string) ($_POST['heure_fin'] ?? ''));
            $idMetierInput = trim((string) ($_POST['id_metier'] ?? ''));

            // validation
            if ($date === '') {
                $errors[] = 'La date est requise.';
            } else {
                $d = \DateTime::createFromFormat('Y-m-d', $date);
                if ($d === false) {
                    $errors[] = 'Format de date invalide.';
                } else {
                    // Vérifier que la date n'est pas dans le passé
                    $today = new \DateTime(date('Y-m-d'));
                    if ($d < $today) {
                        $errors[] = 'Date expirée, veuillez choisir une date disponible';
                    }
                }
            }

            if ($heureDebut === '') {
                $errors[] = 'L\'heure de dÃ©but est requise.';
            }

            if ($heureFin === '') {
                $errors[] = 'L\'heure de fin est requise.';
            }

            // Vérifier que heure_debut < heure_fin
            if ($heureDebut !== '' && $heureFin !== '') {
                $startMinutes = $this->timeToMinutes($heureDebut);
                $endMinutes = $this->timeToMinutes($heureFin);
                
                if ($startMinutes !== null && $endMinutes !== null && $startMinutes >= $endMinutes) {
                    $errors[] = 'Veuillez choisir l\'heure du test convenablement';
                }
            }

            $idMetier = null;

            if ($idMetierInput === '') {
                $errors[] = 'Le choix du mÃ©tier est requis.';
            } else {
                $validated = filter_var($idMetierInput, FILTER_VALIDATE_INT);
                if ($validated === false || (int) $validated <= 0) {
                    $errors[] = 'id_metier invalide.';
                } else {
                    $idMetier = (int) $validated;
                }
            }

            if ($errors === [] && $idMetier !== null) {
                try {
                    $pdo = $this->getConnection();
                    $this->ensureTestTableSchema($pdo);

                    $stmtQ = $pdo->prepare('SELECT id FROM questions WHERE id_metier = :id_metier ORDER BY RAND() LIMIT 3');
                    $stmtQ->execute([':id_metier' => $idMetier]);
                    $questionRows = $stmtQ->fetchAll(PDO::FETCH_ASSOC);

                    if (count($questionRows) < 3) {
                        $errors[] = 'Il faut au moins 3 questions pour le mÃ©tier sÃ©lectionnÃ©.';
                    } else {
                        $testToken = $this->generateTestToken();
                        $stmt = $pdo->prepare('INSERT INTO test (test_token, user_name, user_email, date, heure_debut, heure_fin, id_metier, id_question) VALUES (:test_token, :user_name, :user_email, :date, :heure_debut, :heure_fin, :id_metier, :id_question)');

                        $pdo->beginTransaction();

                        try {
                                    $userName = trim((string) ($_POST['user_name'] ?? '')) ?: null;
                                    $userEmail = trim((string) ($_POST['user_email'] ?? '')) ?: null;

                                    foreach ($questionRows as $questionRow) {
                                        $stmt->execute([
                                            ':test_token' => $testToken,
                                            ':date' => $date,
                                            ':heure_debut' => $heureDebut,
                                            ':heure_fin' => $heureFin,
                                            ':id_metier' => $idMetier,
                                            ':id_question' => (int) ($questionRow['id'] ?? 0),
                                            ':user_name' => $userName,
                                            ':user_email' => $userEmail,
                                        ]);
                                    }

                            $pdo->commit();
                        } catch (PDOException $e) {
                            if ($pdo->inTransaction()) {
                                $pdo->rollBack();
                            }

                            throw $e;
                        }

                        $this->redirect('team/test&token=' . rawurlencode($testToken));
                        return;
                    }
                } catch (PDOException $e) {
                    $dbError = 'Erreur base de donnÃ©es: ' . $e->getMessage();
                }
            }
        }

        // Fetch metiers to render within front page
        $metiers = [];

        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->query(
                'SELECT id_metier, COUNT(*) AS total_questions
                 FROM questions
                 GROUP BY id_metier
                 ORDER BY id_metier ASC'
            );

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $metiers[] = [
                    'id_metier' => (int) ($row['id_metier'] ?? 0),
                    'total_questions' => (int) ($row['total_questions'] ?? 0),
                ];
            }
        } catch (PDOException $e) {
            $dbError = 'Erreur base de donnÃ©es: ' . $e->getMessage();
        }

        $this->renderStandalone('front/front', [
            'metiers' => $metiers,
            'dbError' => $dbError,
            'errors' => $errors,
            'date' => $date,
            'heure_debut' => $heureDebut,
            'heure_fin' => $heureFin,
            'idMetierInput' => $idMetierInput,
        ]);
    }

    public function show(): void
    {
        $testToken = trim((string) ($_GET['token'] ?? ''));

        if ($testToken === '') {
            $this->redirect('team');
        }

        $this->ensureTestTableSchema($this->getConnection());
        $tests = $this->findTestsByToken($testToken);

        if ($tests === []) {
            $this->redirect('team');
        }

        $testPack = $this->buildTestPack($tests);

        if ($testPack === []) {
            $this->renderStandalone('front/test', [
                'testToken' => $testToken,
                'tests' => [],
                'dbError' => 'Question introuvable pour ce test.',
                'validationError' => null,
                'selectedAnswers' => [],
            ]);
            return;
        }

        $this->renderStandalone('front/test', [
            'testToken' => $testToken,
            'tests' => $testPack,
            'dbError' => null,
            'validationError' => null,
            'selectedAnswers' => [],
        ]);
    }

    public function submit(): void
    {
        if ($this->requestMethod() !== 'POST') {
            $this->redirect('team');
        }

        $testToken = trim((string) ($_POST['test_token'] ?? ''));
        $postedAnswers = $_POST['answers'] ?? [];

        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($testToken === '' || !is_array($postedAnswers)) {
            if ($isAjax) {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'ParamÃ¨tres invalides']);
                return;
            }

            $this->redirect('team');
        }

        $this->ensureTestTableSchema($this->getConnection());
        $tests = $this->findTestsByToken($testToken);

        if ($tests === []) {
            if ($isAjax) {
                header('Content-Type: application/json', true, 404);
                echo json_encode(['error' => 'Test introuvable']);
                return;
            }

            $this->redirect('team');
        }

        $testPack = $this->buildTestPack($tests);

        if ($testPack === []) {
            if ($isAjax) {
                header('Content-Type: application/json', true, 404);
                echo json_encode(['error' => 'Question introuvable pour ce test.']);
                return;
            }

            $this->renderStandalone('front/test', [
                'testToken' => $testToken,
                'tests' => [],
                'dbError' => 'Question introuvable pour ce test.',
                'validationError' => null,
                'selectedAnswers' => [],
            ]);
            return;
        }

        if ($testPack === []) {
            $this->renderStandalone('front/test', [
                'testToken' => $testToken,
                'tests' => [],
                'dbError' => 'Question introuvable pour ce test.',
                'validationError' => null,
                'selectedAnswers' => [],
            ]);
            return;
        }

        $answers = [];

        foreach ($postedAnswers as $questionId => $answerId) {
            $answers[(int) $questionId] = (int) $answerId;
        }

        if (count($answers) < count($testPack)) {
            $this->renderStandalone('front/test', [
                'testToken' => $testToken,
                'tests' => $testPack,
                'dbError' => null,
                'validationError' => 'Merci de rÃ©pondre Ã  toutes les questions.',
                'selectedAnswers' => $answers,
            ]);
            return;
        }

        $pdo = $this->getConnection();

        try {
            $result = $this->storeAndScoreAnswers($pdo, $testToken, $testPack, $answers);
        } catch (PDOException $e) {
            $this->renderStandalone('front/test', [
                'testToken' => $testToken,
                'tests' => $testPack,
                'dbError' => 'Erreur base de donnees: ' . $e->getMessage(),
                'validationError' => null,
                'selectedAnswers' => $answers,
            ]);
            return;
        }

        // If this is an AJAX request, return JSON
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($isAjax) {
            header('Content-Type: application/json');
            
            // Build details array for each question
            $details = [];
            foreach ($result['tests'] as $i => $test) {
                $details[] = [
                    'number' => (int) $i + 1,
                    'question' => (string) ($test['question']->getTexte() ?? ''),
                    'selectedAnswer' => (string) ($test['selectedAnswerText'] ?? ''),
                    'correctAnswer' => (string) ($test['correctAnswerText'] ?? ''),
                    'isCorrect' => (bool) $test['isCorrect'],
                ];
            }
            
            echo json_encode([
                'score' => (int) $result['score'],
                'total' => count($testPack),
                'details' => $details,
            ]);
            return;
        }

        // Generate token and save test result
        $resultToken = TokenGenerator::generate(16);
        $resultDetails = [];
        
        foreach ($result['tests'] as $i => $test) {
            $resultDetails[] = [
                'number' => (int) $i + 1,
                'question' => (string) ($test['question']->getTexte() ?? ''),
                'questionId' => (int) ($test['question']->getId() ?? 0),
                'selectedAnswer' => (string) ($test['selectedAnswerText'] ?? ''),
                'correctAnswer' => (string) ($test['correctAnswerText'] ?? ''),
                'isCorrect' => (bool) $test['isCorrect'],
            ];
        }

        try {
            $testResultModel = new TestResult(
                $resultToken,
                $result['score'],
                count($testPack),
                $resultDetails,
                $testToken,
                null // No expiry for now, can be added later
            );
            $testResultModel->save($pdo);
        } catch (PDOException $e) {
            // Log error but don't block result display
            error_log('Error saving test result: ' . $e->getMessage());
        }

        // Build a compact JSON payload to embed directly in the QR code (no URL).
        $correctCount = (int) $result['score'];
        $totalCount = count($testPack);
        $percentage = $totalCount > 0 ? ($correctCount / $totalCount) * 100 : 0;
        $percentInt = (int) round($percentage);
        $passed = $totalCount > 0 && $correctCount >= (int) ceil($totalCount * 0.5);
        $createdAt = (new \DateTime())->format('d/m/Y');
        $message = $passed
            ? 'Bravo, vous avez réussi le test'
            : 'Résultat insuffisant, continuez à réviser';

        $qrData = sprintf(
            '%s ! Votre score est %d/%d. Le pourcentage de réussite est égal à %d%%. Créé le %s',
            $message,
            $correctCount,
            $totalCount,
            $percentInt,
            $createdAt
        );

        $this->renderStandalone('front/test_resultat', [
            'testToken' => $testToken,
            'tests' => $result['tests'],
            'score' => $result['score'],
            'totalQuestions' => count($testPack),
            'resultToken' => $resultToken,
            'qrData' => $qrData,
        ]);

        error_log('[QR DEBUG] qrData: ' . $qrData);
    }

    private function requestMethod(): string
    {
        return strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
    }

    private function getConnection(): PDO
    {
        $pdo = require dirname(__DIR__, 2) . '/db.php';

        if (!$pdo instanceof PDO) {
            throw new PDOException('Connexion PDO invalide depuis db.php');
        }

        return $pdo;
    }

    private function ensureTestTableSchema(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS test (
                id_test INT AUTO_INCREMENT PRIMARY KEY,
                date DATE NOT NULL,
                heure_debut TIME NOT NULL,
                heure_fin TIME NOT NULL,
                id_metier INT NOT NULL,
                id_question INT NOT NULL,
                KEY idx_tests_id_metier (id_metier),
                CONSTRAINT fk_tests_question
                    FOREIGN KEY (id_question)
                    REFERENCES questions(id)
                    ON DELETE RESTRICT
                    ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci'
        );

        $stmtColumn = $pdo->query("SHOW COLUMNS FROM test LIKE 'test_token'");
        $hasTestToken = $stmtColumn !== false && $stmtColumn->fetch(PDO::FETCH_ASSOC) !== false;

        if (!$hasTestToken) {
            $pdo->exec(
                'ALTER TABLE test
                 ADD COLUMN test_token VARCHAR(64) NOT NULL AFTER id_test'
            );
        }

        // ensure user_name and user_email columns exist
        $stmtUserName = $pdo->query("SHOW COLUMNS FROM test LIKE 'user_name'");
        $hasUserName = $stmtUserName !== false && $stmtUserName->fetch(PDO::FETCH_ASSOC) !== false;

        if (!$hasUserName) {
            $pdo->exec(
                "ALTER TABLE test ADD COLUMN user_name VARCHAR(255) NULL AFTER test_token"
            );
        }

        $stmtUserEmail = $pdo->query("SHOW COLUMNS FROM test LIKE 'user_email'");
        $hasUserEmail = $stmtUserEmail !== false && $stmtUserEmail->fetch(PDO::FETCH_ASSOC) !== false;

        if (!$hasUserEmail) {
            $pdo->exec(
                "ALTER TABLE test ADD COLUMN user_email VARCHAR(255) NULL AFTER user_name"
            );
        }

        $stmtIndex = $pdo->query("SHOW INDEX FROM test WHERE Key_name = 'idx_test_token'");
        $hasIndex = $stmtIndex !== false && $stmtIndex->fetch(PDO::FETCH_ASSOC) !== false;

        if (!$hasIndex) {
            $pdo->exec('ALTER TABLE test ADD KEY idx_test_token (test_token)');
        }
    }

    /**
     * @return array<int, Test>
     */
    private function findTestsByToken(string $testToken): array
    {
        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->prepare('SELECT id_test, test_token, user_name, user_email, date, heure_debut, heure_fin, id_metier, id_question FROM test WHERE test_token = :test_token ORDER BY id_test ASC');
            $stmt->execute([':test_token' => $testToken]);

            $tests = [];

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $tests[] = new Test(
                    isset($row['id_test']) ? (int) $row['id_test'] : null,
                    (string) ($row['date'] ?? ''),
                    (string) ($row['heure_debut'] ?? ''),
                    (string) ($row['heure_fin'] ?? ''),
                    (int) ($row['id_metier'] ?? 0),
                    (int) ($row['id_question'] ?? 0),
                    isset($row['user_name']) ? (string) $row['user_name'] : null,
                    isset($row['user_email']) ? (string) $row['user_email'] : null
                );
            }

            return $tests;
        } catch (PDOException) {
            return [];
        }
    }

    /**
     * @param array<int, Test> $tests
     * @return array<int, array{test: Test, question: Question, reponses: array<int, Reponse>}>
     */
    private function buildTestPack(array $tests): array
    {
        $pack = [];

        foreach ($tests as $test) {
            $questionData = $this->findQuestionDataById((int) $test->getIdQuestion());

            if ($questionData === null) {
                return [];
            }

            $pack[] = [
                'test' => $test,
                'question' => $questionData['question'],
                'reponses' => $questionData['reponses'],
            ];
        }

        return $pack;
    }

    /**
     * @return array{question: Question, reponses: array<int, Reponse>}|null
     */
    private function findQuestionDataById(int $questionId): ?array
    {
        try {
            $pdo = $this->getConnection();

            $stmtQuestion = $pdo->prepare('SELECT id, texte, id_metier FROM questions WHERE id = :id LIMIT 1');
            $stmtQuestion->execute([':id' => $questionId]);
            $questionRow = $stmtQuestion->fetch(PDO::FETCH_ASSOC);

            if ($questionRow === false) {
                return null;
            }

            $question = new Question(
                isset($questionRow['id']) ? (int) $questionRow['id'] : null,
                (string) ($questionRow['texte'] ?? ''),
                (int) ($questionRow['id_metier'] ?? 0)
            );

            $stmtReponses = $pdo->prepare('SELECT id, texte, est_correcte, id_question FROM reponses WHERE id_question = :id_question ORDER BY id ASC');
            $stmtReponses->execute([':id_question' => $questionId]);

            $reponses = [];

            foreach ($stmtReponses->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $reponses[] = new Reponse(
                    isset($row['id']) ? (int) $row['id'] : null,
                    (string) ($row['texte'] ?? ''),
                    ((int) ($row['est_correcte'] ?? 0)) === 1,
                    (int) ($row['id_question'] ?? 0)
                );
            }

            return [
                'question' => $question,
                'reponses' => $reponses,
            ];
        } catch (PDOException) {
            return null;
        }
    }

    private function findCorrectAnswerId(PDO $pdo, int $questionId): ?int
    {
        $stmt = $pdo->prepare('SELECT id FROM reponses WHERE id_question = :id_question AND est_correcte = 1 LIMIT 1');
        $stmt->execute([':id_question' => $questionId]);
        $value = $stmt->fetchColumn();

        if ($value === false) {
            return null;
        }

        return (int) $value;
    }

    /**
     * @param array<int, array{test: Test, question: Question, reponses: array<int, Reponse>}> $testPack
     * @param array<int, int> $answers
     * @return array{score: int, tests: array<int, array{test: Test, question: Question, reponses: array<int, Reponse>, selectedAnswer: int, correctAnswerId: int, isCorrect: bool}>}
     */
    private function storeAndScoreAnswers(PDO $pdo, string $testToken, array $testPack, array $answers): array
    {
        $this->ensureUserAnswersTable($pdo);
        $attemptToken = $this->generateAttemptToken();
        $score = 0;
        $annotatedTests = [];

        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare(
                'INSERT INTO reponses_utilisateurs (id_question, id_reponse, attempt_token, est_correcte)
                 VALUES (:id_question, :id_reponse, :attempt_token, :est_correcte)'
            );

            foreach ($testPack as $item) {
                $question = $item['question'];
                $questionId = (int) ($question->getId() ?? 0);
                $selectedAnswerId = (int) ($answers[$questionId] ?? 0);
                $correctAnswerId = $this->findCorrectAnswerId($pdo, $questionId);
                $isCorrect = $correctAnswerId !== null && $selectedAnswerId === $correctAnswerId;

                if ($isCorrect) {
                    $score++;
                }

                $stmt->execute([
                    ':id_question' => $questionId,
                    ':id_reponse' => $selectedAnswerId,
                    ':attempt_token' => $attemptToken,
                    ':est_correcte' => $isCorrect ? 1 : 0,
                ]);

                $annotatedTests[] = [
                    'test' => $item['test'],
                    'question' => $question,
                    'reponses' => $item['reponses'],
                    'selectedAnswer' => $selectedAnswerId,
                    'selectedAnswerIndex' => $this->findAnswerIndex($item['reponses'], $selectedAnswerId),
                    'selectedAnswerText' => $this->findAnswerText($item['reponses'], $selectedAnswerId),
                    'correctAnswerId' => $correctAnswerId ?? 0,
                    'correctAnswerIndex' => $this->findAnswerIndex($item['reponses'], $correctAnswerId ?? 0),
                    'correctAnswerText' => $this->findAnswerText($item['reponses'], $correctAnswerId ?? 0),
                    'isCorrect' => $isCorrect,
                ];
            }

            $pdo->commit();
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }

        return [
            'score' => $score,
            'tests' => $annotatedTests,
        ];
    }

    private function ensureUserAnswersTable(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS reponses_utilisateurs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_question INT NOT NULL,
                id_reponse INT NOT NULL,
                attempt_token VARCHAR(64) NOT NULL DEFAULT "",
                est_correcte BOOLEAN NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                KEY idx_rep_user_question (id_question),
                KEY idx_rep_user_correct (est_correcte),
                KEY idx_rep_user_reponse (id_reponse),
                KEY idx_rep_user_attempt (attempt_token),
                CONSTRAINT fk_rep_user_question
                    FOREIGN KEY (id_question)
                    REFERENCES questions(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,
                CONSTRAINT fk_rep_user_reponse
                    FOREIGN KEY (id_reponse)
                    REFERENCES reponses(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci'
        );

        $stmtColumn = $pdo->query("SHOW COLUMNS FROM reponses_utilisateurs LIKE 'attempt_token'");
        $hasAttemptToken = $stmtColumn !== false && $stmtColumn->fetch(PDO::FETCH_ASSOC) !== false;

        if (!$hasAttemptToken) {
            $pdo->exec(
                'ALTER TABLE reponses_utilisateurs
                 ADD COLUMN attempt_token VARCHAR(64) NOT NULL DEFAULT "" AFTER id_reponse,
                 ADD KEY idx_rep_user_attempt (attempt_token)'
            );
        }
    }

    private function generateAttemptToken(): string
    {
        return str_replace('.', '', uniqid('attempt_', true));
    }

    private function generateTestToken(): string
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * @param array<int, Reponse> $reponses
     */
    private function findAnswerIndex(array $reponses, int $answerId): int
    {
        $index = 0;

        foreach ($reponses as $i => $rep) {
            $index = $i + 1;

            if ($rep->getId() === $answerId) {
                return $index;
            }
        }

        return 0;
    }

    /**
     * @param array<int, Reponse> $reponses
     */
    private function findAnswerText(array $reponses, int $answerId): string
    {
        foreach ($reponses as $rep) {
            if ($rep->getId() === $answerId) {
                return (string) $rep->getTexte();
            }
        }

        return '';
    }

    /**
     * Convertit une heure au format HH:mm en minutes
     * @param string $time Format HH:mm ou HH
     * @return int|null Nombre de minutes ou null si format invalide
     */
    private function timeToMinutes(string $time): ?int
    {
        $parts = explode(':', $time);
        
        if (count($parts) < 1 || count($parts) > 2) {
            return null;
        }

        $hours = (int) ($parts[0] ?? 0);
        $minutes = (int) ($parts[1] ?? 0);

        if ($hours < 0 || $hours > 23 || $minutes < 0 || $minutes > 59) {
            return null;
        }

        return $hours * 60 + $minutes;
    }
}

