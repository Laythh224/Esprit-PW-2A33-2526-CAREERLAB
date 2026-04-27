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
        $idMetierInput = '';
        $dbError = null;

        if (($this->requestMethod()) === 'POST') {
            $texte = trim((string) ($_POST['question'] ?? ''));
            $idMetierInput = trim((string) ($_POST['id_metier'] ?? ''));
            $validation = $this->validateInput($texte, $idMetierInput);
            $errors = $validation['errors'];
            $idMetier = $validation['idMetier'];

            if ($errors === [] && $idMetier !== null) {
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
            'idMetierInput' => $idMetierInput,
            'dbError' => $dbError,
        ]);
    }

    public function getAllQuestions(): void
    {
        $questions = [];
        $reponsesByQuestion = [];
        $totalQuestions = 0;
        $questionsByMetier = [];
        $dbError = null;
        $message = (string) ($_GET['msg'] ?? '');
        $keyword = trim((string) ($_GET['q'] ?? ''));
        $searchIdInput = trim((string) ($_GET['search_id'] ?? ''));
        $sort = $this->resolveSort((string) ($_GET['sort'] ?? 'id_desc'));
        $searchErrors = [];
        $searchId = null;

        if ($searchIdInput !== '') {
            $validatedId = filter_var($searchIdInput, FILTER_VALIDATE_INT);

            if ($validatedId === false || (int) $validatedId <= 0) {
                $searchErrors[] = 'Le critere id doit etre un entier valide.';
            } else {
                $searchId = (int) $validatedId;
            }
        }

        try {
            $pdo = $this->getConnection();
            $sql = 'SELECT id, texte, id_metier FROM questions';
            $conditions = [];
            $params = [];

            if ($keyword !== '') {
                $conditions[] = 'texte LIKE :keyword';
                $params[':keyword'] = '%' . $keyword . '%';
            }

            if ($searchId !== null) {
                $conditions[] = 'id = :search_id';
                $params[':search_id'] = $searchId;
            }

            if ($conditions !== []) {
                $sql .= ' WHERE ' . implode(' AND ', $conditions);
            }

            $sql .= ' ORDER BY ' . $this->sortToOrderBy($sort);

            $stmtQuestions = $pdo->prepare($sql);
            $stmtQuestions->execute($params);

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

            $totalQuestionsResult = $pdo->query('SELECT COUNT(*) FROM questions');

            if ($totalQuestionsResult !== false) {
                $totalQuestions = (int) $totalQuestionsResult->fetchColumn();
            }

            $stmtByMetier = $pdo->query('SELECT id_metier, COUNT(*) AS total FROM questions GROUP BY id_metier ORDER BY id_metier ASC');

            foreach ($stmtByMetier->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $metierId = (int) ($row['id_metier'] ?? 0);
                $questionsByMetier[$metierId] = (int) ($row['total'] ?? 0);
            }
        } catch (PDOException $e) {
            $dbError = 'Erreur base de donnees: ' . $e->getMessage();
        }

        $this->renderStandalone('questions/list', [
            'questions' => $questions,
            'reponsesByQuestion' => $reponsesByQuestion,
            'totalQuestions' => $totalQuestions,
            'questionsByMetier' => $questionsByMetier,
            'keyword' => $keyword,
            'searchIdInput' => $searchIdInput,
            'sort' => $sort,
            'searchErrors' => $searchErrors,
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
            $idMetierInput = trim((string) ($_POST['id_metier'] ?? ''));
            $validation = $this->validateInput($texte, $idMetierInput);
            $errors = $validation['errors'];
            $idMetier = $validation['idMetier'];

            if ($errors === [] && $idMetier !== null) {
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

            $question = new Question($id, $texte, $idMetier ?? 0);
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
     * @return array{errors: array<int, string>, idMetier: ?int}
     */
    private function validateInput(string $texte, string $idMetierInput): array
    {
        $errors = [];
        $idMetier = null;
        $texteSansEspaces = rtrim($texte);

        if ($texte === '') {
            $errors[] = 'La question ne doit pas etre vide.';
        }

        if ($texteSansEspaces !== '' && !str_ends_with($texteSansEspaces, '?')) {
            $errors[] = 'La question doit se terminer par un point d\'interrogation (?).';
        }

        if ($idMetierInput === '') {
            $errors[] = 'id_metier ne doit pas etre vide.';
        } else {
            $validatedId = filter_var($idMetierInput, FILTER_VALIDATE_INT);

            if ($validatedId === false || (int) $validatedId <= 0) {
                $errors[] = 'id_metier doit etre un entier valide.';
            } else {
                $idMetier = (int) $validatedId;
            }
        }

        return [
            'errors' => $errors,
            'idMetier' => $idMetier,
        ];
    }

    private function resolveSort(string $sort): string
    {
        $normalizedSort = strtolower(trim($sort));
        $allowedSorts = ['id_asc', 'id_desc', 'id_metier_asc', 'id_metier_desc'];

        if (!in_array($normalizedSort, $allowedSorts, true)) {
            return 'id_desc';
        }

        return $normalizedSort;
    }

    private function sortToOrderBy(string $sort): string
    {
        $map = [
            'id_asc' => 'id ASC',
            'id_desc' => 'id DESC',
            'id_metier_asc' => 'id_metier ASC, id ASC',
            'id_metier_desc' => 'id_metier DESC, id DESC',
        ];

        return $map[$sort] ?? $map['id_desc'];
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
