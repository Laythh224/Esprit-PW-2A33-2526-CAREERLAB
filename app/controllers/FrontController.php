<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Question;
use App\Models\Reponse;
use PDO;
use PDOException;

final class FrontController extends Controller
{
    public function afficherMetiers(): void
    {
        $metiers = [];
        $dbError = null;

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
            $dbError = 'Erreur base de donnees: ' . $e->getMessage();
        }

        $this->renderStandalone('front/front', [
            'metiers' => $metiers,
            'dbError' => $dbError,
        ]);
    }

    public function afficherQuiz(?int $id_metier = null): void
    {
        $idMetier = $id_metier ?? (int) ($_GET['id_metier'] ?? 0);

        if ($idMetier <= 0) {
            $this->redirect('team');
        }

        $dbError = null;
        $questions = [];

        try {
            $questions = $this->fetchQuestionsByMetier($idMetier);
        } catch (PDOException $e) {
            $dbError = 'Erreur base de donnees: ' . $e->getMessage();
        }

        $this->renderStandalone('front/quiz', [
            'idMetier' => $idMetier,
            'questions' => $questions,
            'dbError' => $dbError,
            'validationError' => null,
            'selectedAnswers' => [],
        ]);
    }

    public function traiterQuiz(): void
    {
        if ($this->requestMethod() !== 'POST') {
            $this->redirect('team');
        }

        $idMetier = (int) ($_POST['id_metier'] ?? 0);
        $postedAnswers = $_POST['answers'] ?? [];

        if ($idMetier <= 0 || !is_array($postedAnswers)) {
            $this->redirect('team');
        }

        $answers = [];

        foreach ($postedAnswers as $questionId => $reponseId) {
            $answers[(int) $questionId] = (int) $reponseId;
        }

        $dbError = null;

        try {
            $pdo = $this->getConnection();
            $stmtQuestions = $pdo->prepare('SELECT id FROM questions WHERE id_metier = :id_metier ORDER BY id ASC');
            $stmtQuestions->execute([':id_metier' => $idMetier]);
            $questionIds = array_map('intval', $stmtQuestions->fetchAll(PDO::FETCH_COLUMN));

            if ($questionIds === []) {
                $this->renderStandalone('front/resultat', [
                    'score' => 0,
                    'totalQuestions' => 0,
                    'niveau' => 'Debutant',
                    'idMetier' => $idMetier,
                    'dbError' => null,
                ]);
                return;
            }

            $missing = array_filter(
                $questionIds,
                static fn (int $questionId): bool => !isset($answers[$questionId]) || $answers[$questionId] <= 0
            );

            if ($missing !== []) {
                $this->renderStandalone('front/quiz', [
                    'idMetier' => $idMetier,
                    'questions' => $this->fetchQuestionsByMetier($idMetier),
                    'dbError' => null,
                    'validationError' => 'Merci de repondre a toutes les questions.',
                    'selectedAnswers' => $answers,
                ]);
                return;
            }

            $placeholders = implode(',', array_fill(0, count($questionIds), '?'));
            $stmtCorrect = $pdo->prepare(
                'SELECT id_question, id AS id_reponse
                 FROM reponses
                 WHERE est_correcte = 1
                 AND id_question IN (' . $placeholders . ')'
            );
            $stmtCorrect->execute($questionIds);

            $correctByQuestion = [];

            foreach ($stmtCorrect->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $correctByQuestion[(int) ($row['id_question'] ?? 0)] = (int) ($row['id_reponse'] ?? 0);
            }

            $score = 0;

            foreach ($questionIds as $questionId) {
                if (($answers[$questionId] ?? 0) === ($correctByQuestion[$questionId] ?? -1)) {
                    $score++;
                }
            }

            $this->renderStandalone('front/resultat', [
                'score' => $score,
                'totalQuestions' => count($questionIds),
                'niveau' => $this->resolveNiveau($score),
                'idMetier' => $idMetier,
                'dbError' => null,
            ]);
            return;
        } catch (PDOException $e) {
            $dbError = 'Erreur base de donnees: ' . $e->getMessage();
        }

        $this->renderStandalone('front/resultat', [
            'score' => 0,
            'totalQuestions' => 0,
            'niveau' => 'Debutant',
            'idMetier' => $idMetier,
            'dbError' => $dbError,
        ]);
    }

    /**
     * @return array<int, array{question: Question, reponses: array<int, Reponse>}>
     */
    private function fetchQuestionsByMetier(int $idMetier): array
    {
        $pdo = $this->getConnection();

        $stmt = $pdo->prepare(
            'SELECT q.id AS id_question,
                    q.texte AS question,
                    r.id AS id_reponse,
                    r.texte AS reponse,
                    r.est_correcte
             FROM questions q
             JOIN reponses r ON q.id = r.id_question
             WHERE q.id_metier = :id_metier
             ORDER BY RAND()'
        );

        $stmt->execute([':id_metier' => $idMetier]);

        $questionsMap = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $questionId = (int) ($row['id_question'] ?? 0);

            if (!isset($questionsMap[$questionId])) {
                $questionsMap[$questionId] = [
                    'question' => new Question(
                        $questionId,
                        (string) ($row['question'] ?? ''),
                        $idMetier
                    ),
                    'reponses' => [],
                ];
            }

            $questionsMap[$questionId]['reponses'][] = new Reponse(
                (int) ($row['id_reponse'] ?? 0),
                (string) ($row['reponse'] ?? ''),
                ((int) ($row['est_correcte'] ?? 0)) === 1,
                $questionId
            );
        }

        return array_values($questionsMap);
    }

    private function resolveNiveau(int $score): string
    {
        if ($score <= 1) {
            return 'Debutant';
        }

        if ($score === 2) {
            return 'Intermediaire';
        }

        return 'Expert';
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
}
