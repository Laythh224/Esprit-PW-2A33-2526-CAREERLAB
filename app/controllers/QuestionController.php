<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Question;
use App\Models\Reponse;
use PDO;
use PDOException;

final class QuestionController extends Controller
{
    public function evaluation(): void
    {
        $entity = strtolower(trim((string) ($_GET['entity'] ?? 'question')));
        $action = strtolower(trim((string) ($_GET['action'] ?? 'list')));

        if ($entity === 'reponse') {
            $reponseController = new ReponseController();

            if ($action === 'add') {
                $reponseController->addReponse();
                return;
            }

            if ($action === 'edit') {
                $reponseController->updateReponse();
                return;
            }

            if ($action === 'delete') {
                $reponseController->deleteReponse();
                return;
            }

            $reponseController->getAllReponses();
            return;
        }

        if ($action === 'add') {
            $this->addQuestion();
            return;
        }

        if ($action === 'edit') {
            $this->updateQuestion();
            return;
        }

        if ($action === 'delete') {
            $this->deleteQuestion();
            return;
        }

        $this->getAllQuestions();
    }

    public function addQuestion(): void
    {
        $errors = [];
        $texte = '';
        $idMetier = 1;
        $dbError = null;

        if (($this->requestMethod()) === 'POST') {
            $texte = trim((string) ($_POST['question'] ?? ''));
            $idMetier = (int) ($_POST['id_metier'] ?? 0);
            $errors = $this->validateInput($texte, $idMetier);

            if ($errors === []) {
                try {
                    $pdo = $this->getConnection();
                    $stmt = $pdo->prepare('INSERT INTO questions (texte, id_metier) VALUES (:texte, :id_metier)');
                    $stmt->execute([
                        ':texte' => $texte,
                        ':id_metier' => $idMetier,
                    ]);

                    $this->redirect('evaluation&msg=added');
                } catch (PDOException $e) {
                    $dbError = 'Erreur base de donnees: ' . $e->getMessage();
                }
            }
        }

        $this->renderStandalone('questions/add', [
            'errors' => $errors,
            'texte' => $texte,
            'idMetier' => $idMetier,
            'dbError' => $dbError,
        ]);
    }

    public function getAllQuestions(): void
    {
        $questions = [];
        $reponsesByQuestion = [];
        $dbError = null;
        $message = (string) ($_GET['msg'] ?? '');

        try {
            $pdo = $this->getConnection();
            $stmtQuestions = $pdo->query('SELECT id, texte, id_metier FROM questions ORDER BY id DESC');

            foreach ($stmtQuestions->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $questions[] = new Question(
                    isset($row['id']) ? (int) $row['id'] : null,
                    (string) ($row['texte'] ?? ''),
                    (int) ($row['id_metier'] ?? 0)
                );
            }

            $stmtReponses = $pdo->query('SELECT id, texte, est_correcte, id_question FROM reponses ORDER BY id ASC');

            foreach ($stmtReponses->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $questionId = (int) ($row['id_question'] ?? 0);

                if (!isset($reponsesByQuestion[$questionId])) {
                    $reponsesByQuestion[$questionId] = [];
                }

                $reponsesByQuestion[$questionId][] = new Reponse(
                    isset($row['id']) ? (int) $row['id'] : null,
                    (string) ($row['texte'] ?? ''),
                    ((int) ($row['est_correcte'] ?? 0)) === 1,
                    $questionId
                );
            }
        } catch (PDOException $e) {
            $dbError = 'Erreur base de donnees: ' . $e->getMessage();
        }

        $this->renderStandalone('questions/list', [
            'questions' => $questions,
            'reponsesByQuestion' => $reponsesByQuestion,
            'dbError' => $dbError,
            'message' => $message,
        ]);
    }

    public function updateQuestion(): void
    {
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->redirect('evaluation&msg=invalid_id');
        }

        $errors = [];
        $dbError = null;
        $question = null;

        if (($this->requestMethod()) === 'POST') {
            $texte = trim((string) ($_POST['question'] ?? ''));
            $idMetier = (int) ($_POST['id_metier'] ?? 0);
            $errors = $this->validateInput($texte, $idMetier);

            if ($errors === []) {
                try {
                    $pdo = $this->getConnection();
                    $stmt = $pdo->prepare('UPDATE questions SET texte = :texte, id_metier = :id_metier WHERE id = :id');
                    $stmt->execute([
                        ':texte' => $texte,
                        ':id_metier' => $idMetier,
                        ':id' => $id,
                    ]);

                    $this->redirect('evaluation&msg=updated');
                } catch (PDOException $e) {
                    $dbError = 'Erreur base de donnees: ' . $e->getMessage();
                }
            }

            $question = new Question($id, $texte, $idMetier);
        }

        if ($question === null) {
            $question = $this->findQuestionById($id);
        }

        if ($question === null) {
            $this->redirect('evaluation&msg=not_found');
        }

        $this->renderStandalone('questions/edit', [
            'question' => $question,
            'errors' => $errors,
            'dbError' => $dbError,
        ]);
    }

    public function deleteQuestion(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->redirect('evaluation&msg=invalid_id');
        }

        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->prepare('DELETE FROM questions WHERE id = :id');
            $stmt->execute([':id' => $id]);
            $this->redirect('evaluation&msg=deleted');
        } catch (PDOException $e) {
            $this->redirect('evaluation&msg=db_error');
        }
    }

    private function requestMethod(): string
    {
        return strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
    }

    /**
     * @return array<int, string>
     */
    private function validateInput(string $texte, int $idMetier): array
    {
        $errors = [];

        if ($texte === '') {
            $errors[] = 'La question ne doit pas etre vide.';
        }

        if ($texte !== '' && !str_ends_with($texte, '?')) {
            $errors[] = 'La question doit se terminer par ?';
        }

        if ($idMetier <= 0) {
            $errors[] = 'id_metier doit etre un entier positif.';
        }

        return $errors;
    }

    private function findQuestionById(int $id): ?Question
    {
        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->prepare('SELECT id, texte, id_metier FROM questions WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row === false) {
                return null;
            }

            return new Question(
                isset($row['id']) ? (int) $row['id'] : null,
                (string) ($row['texte'] ?? ''),
                (int) ($row['id_metier'] ?? 0)
            );
        } catch (PDOException) {
            return null;
        }
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
