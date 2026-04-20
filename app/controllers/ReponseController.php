<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Question;
use App\Models\Reponse;
use PDO;
use PDOException;

final class ReponseController extends Controller
{
    public function addReponse(): void
    {
        $errors = [];
        $dbError = null;
        $texte = '';
        $estCorrecte = false;
        $idQuestion = (int) ($_GET['id_question'] ?? 0);

        if ($this->requestMethod() === 'POST') {
            $texte = trim((string) ($_POST['texte'] ?? ''));
            $estCorrecte = ((int) ($_POST['est_correcte'] ?? 0)) === 1;
            $idQuestion = (int) ($_POST['id_question'] ?? 0);
            $errors = $this->validateInput($texte, $idQuestion);

            if ($errors === []) {
                try {
                    $pdo = $this->getConnection();
                    $stmt = $pdo->prepare('INSERT INTO reponses (texte, est_correcte, id_question) VALUES (?, ?, ?)');
                    $stmt->execute([$texte, $estCorrecte ? 1 : 0, $idQuestion]);

                    $this->redirect('evaluation&msg=reponse_added');
                } catch (PDOException $e) {
                    $dbError = 'Erreur base de donnees: ' . $e->getMessage();
                }
            }
        }

        $this->renderStandalone('reponses/add', [
            'errors' => $errors,
            'dbError' => $dbError,
            'texte' => $texte,
            'estCorrecte' => $estCorrecte,
            'idQuestion' => $idQuestion,
            'questions' => $this->getQuestionOptions(),
        ]);
    }

    public function getAllReponses(): void
    {
        $dbError = null;
        $reponses = [];

        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->query('SELECT id, texte, est_correcte, id_question FROM reponses ORDER BY id DESC');

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $reponses[] = new Reponse(
                    isset($row['id']) ? (int) $row['id'] : null,
                    (string) ($row['texte'] ?? ''),
                    ((int) ($row['est_correcte'] ?? 0)) === 1,
                    (int) ($row['id_question'] ?? 0)
                );
            }
        } catch (PDOException $e) {
            $dbError = 'Erreur base de donnees: ' . $e->getMessage();
        }

        $this->renderStandalone('reponses/list', [
            'reponses' => $reponses,
            'dbError' => $dbError,
            'message' => (string) ($_GET['msg'] ?? ''),
        ]);
    }

    public function getReponseById(int $id): ?Reponse
    {
        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->prepare('SELECT id, texte, est_correcte, id_question FROM reponses WHERE id = ? LIMIT 1');
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row === false) {
                return null;
            }

            return new Reponse(
                isset($row['id']) ? (int) $row['id'] : null,
                (string) ($row['texte'] ?? ''),
                ((int) ($row['est_correcte'] ?? 0)) === 1,
                (int) ($row['id_question'] ?? 0)
            );
        } catch (PDOException) {
            return null;
        }
    }

    public function updateReponse(): void
    {
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->redirect('evaluation&msg=invalid_id');
        }

        $errors = [];
        $dbError = null;
        $reponse = null;

        if ($this->requestMethod() === 'POST') {
            $texte = trim((string) ($_POST['texte'] ?? ''));
            $estCorrecte = ((int) ($_POST['est_correcte'] ?? 0)) === 1;
            $idQuestion = (int) ($_POST['id_question'] ?? 0);
            $errors = $this->validateInput($texte, $idQuestion);

            if ($errors === []) {
                try {
                    $pdo = $this->getConnection();
                    $stmt = $pdo->prepare('UPDATE reponses SET texte = ?, est_correcte = ?, id_question = ? WHERE id = ?');
                    $stmt->execute([$texte, $estCorrecte ? 1 : 0, $idQuestion, $id]);

                    $this->redirect('evaluation&msg=reponse_updated');
                } catch (PDOException $e) {
                    $dbError = 'Erreur base de donnees: ' . $e->getMessage();
                }
            }

            $reponse = new Reponse($id, $texte, $estCorrecte, $idQuestion);
        }

        if ($reponse === null) {
            $reponse = $this->getReponseById($id);
        }

        if ($reponse === null) {
            $this->redirect('evaluation&msg=not_found');
        }

        $this->renderStandalone('reponses/edit', [
            'reponse' => $reponse,
            'errors' => $errors,
            'dbError' => $dbError,
            'questions' => $this->getQuestionOptions(),
        ]);
    }

    public function deleteReponse(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->redirect('evaluation&msg=invalid_id');
        }

        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->prepare('DELETE FROM reponses WHERE id = ?');
            $stmt->execute([$id]);
            $this->redirect('evaluation&msg=reponse_deleted');
        } catch (PDOException) {
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
    private function validateInput(string $texte, int $idQuestion): array
    {
        $errors = [];

        if ($texte === '') {
            $errors[] = 'La reponse ne doit pas etre vide.';
        }

        if ($texte !== '' && mb_strlen($texte) < 3) {
            $errors[] = 'La reponse doit contenir au moins 3 caracteres.';
        }

        if ($idQuestion <= 0) {
            $errors[] = 'id_question doit etre un entier positif.';
        }

        return $errors;
    }

    /**
     * @return array<int, Question>
     */
    private function getQuestionOptions(): array
    {
        $questions = [];

        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->query('SELECT id, texte, id_metier FROM questions ORDER BY id DESC');

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $questions[] = new Question(
                    isset($row['id']) ? (int) $row['id'] : null,
                    (string) ($row['texte'] ?? ''),
                    (int) ($row['id_metier'] ?? 0)
                );
            }
        } catch (PDOException) {
            return [];
        }

        return $questions;
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
