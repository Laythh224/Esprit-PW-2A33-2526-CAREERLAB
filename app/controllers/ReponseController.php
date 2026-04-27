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
        $idQuestionInput = trim((string) ($_GET['id_question'] ?? ''));

        if ($idQuestionInput !== '') {
            $prefillId = filter_var($idQuestionInput, FILTER_VALIDATE_INT);

            if ($prefillId === false || (int) $prefillId <= 0) {
                $idQuestionInput = '';
            }
        }

        if ($this->requestMethod() === 'POST') {
            $texte = trim((string) ($_POST['texte'] ?? ''));
            $estCorrecte = ((int) ($_POST['est_correcte'] ?? 0)) === 1;
            $idQuestionInput = trim((string) ($_POST['id_question'] ?? ''));
            $validation = $this->validateInput($texte, $idQuestionInput);
            $errors = $validation['errors'];
            $idQuestion = $validation['idQuestion'];

            if ($errors === [] && $idQuestion !== null) {
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
            'idQuestionInput' => $idQuestionInput,
            'questions' => $this->getQuestionOptions(),
        ]);
    }

    public function getAllReponses(): void
    {
        $dbError = null;
        $reponses = [];
        $totalReponses = 0;
        $reponsesByQuestion = [];
        $rankingLabels = [];
        $rankingRates = [];
        $rankingScores = [];
        $rankingLevels = [];
        $rankingColors = [];
        $metierSuccessLabels = [];
        $metierSuccessRates = [];
        $sort = $this->resolveSort((string) ($_GET['sort'] ?? 'id_desc'));
        $searchTexte = trim((string) ($_GET['search_texte'] ?? ''));
        $searchEstCorrecte = trim((string) ($_GET['search_est_correcte'] ?? ''));

        if (!in_array($searchEstCorrecte, ['', '0', '1'], true)) {
            $searchEstCorrecte = '';
        }

        try {
            $pdo = $this->getConnection();
            $this->ensureUserAnswersTable($pdo);
            $pdo->exec(
                "UPDATE reponses_utilisateurs
                 SET attempt_token = CONCAT('legacy_', DATE_FORMAT(created_at, '%Y%m%d%H%i%s'))
                 WHERE attempt_token = ''"
            );
            $sqlReponses = 'SELECT id, texte, est_correcte, id_question FROM reponses';
            $conditions = [];
            $params = [];

            if ($searchTexte !== '') {
                $conditions[] = 'texte LIKE :search_texte';
                $params[':search_texte'] = '%' . $searchTexte . '%';
            }

            if ($searchEstCorrecte !== '') {
                $conditions[] = 'est_correcte = :search_est_correcte';
                $params[':search_est_correcte'] = (int) $searchEstCorrecte;
            }

            if ($conditions !== []) {
                $sqlReponses .= ' WHERE ' . implode(' AND ', $conditions);
            }

            $sqlReponses .= ' ORDER BY ' . $this->sortToOrderBy($sort);
            $stmt = $pdo->prepare($sqlReponses);
            $stmt->execute($params);

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $reponses[] = new Reponse(
                    isset($row['id']) ? (int) $row['id'] : null,
                    (string) ($row['texte'] ?? ''),
                    ((int) ($row['est_correcte'] ?? 0)) === 1,
                    (int) ($row['id_question'] ?? 0)
                );
            }

            $totalReponsesResult = $pdo->query('SELECT COUNT(*) FROM reponses');

            if ($totalReponsesResult !== false) {
                $totalReponses = (int) $totalReponsesResult->fetchColumn();
            }

            $stmtByQuestion = $pdo->query('SELECT id_question, COUNT(*) AS total FROM reponses GROUP BY id_question ORDER BY id_question ASC');

            foreach ($stmtByQuestion->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $questionId = (int) ($row['id_question'] ?? 0);
                $reponsesByQuestion[$questionId] = (int) ($row['total'] ?? 0);
            }

            $stmtRanking = $pdo->query(
                "SELECT
                    attempt_token,
                    SUM(CASE WHEN est_correcte = 1 THEN 1 ELSE 0 END) AS total_correct,
                    COUNT(*) AS total_answers,
                    MAX(created_at) AS attempted_at
                 FROM reponses_utilisateurs
                 WHERE attempt_token <> ''
                 GROUP BY attempt_token
                 ORDER BY total_correct DESC, total_answers ASC, attempted_at ASC"
            );

            $rank = 1;

            foreach ($stmtRanking->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $totalCorrect = (int) ($row['total_correct'] ?? 0);
                $totalAnswers = (int) ($row['total_answers'] ?? 0);

                if ($totalAnswers <= 0) {
                    continue;
                }

                $level = $this->resolveNiveau($totalCorrect);
                $rankingLabels[] = 'Utilisateur ' . $rank;
                $rankingRates[] = round(($totalCorrect / $totalAnswers) * 100, 2);
                $rankingScores[] = $totalCorrect . '/' . $totalAnswers;
                $rankingLevels[] = $level;
                $rankingColors[] = $this->resolveNiveauColor($level);
                $rank++;
            }

            $stmtMetierRates = $pdo->query(
                'SELECT
                    q.id_metier,
                    SUM(CASE WHEN ru.est_correcte = 1 THEN 1 ELSE 0 END) AS total_correct,
                    COUNT(*) AS total_answers
                 FROM reponses_utilisateurs ru
                 INNER JOIN questions q ON q.id = ru.id_question
                 INNER JOIN reponses r ON r.id = ru.id_reponse AND r.id_question = q.id
                 GROUP BY q.id_metier
                 ORDER BY q.id_metier ASC'
            );

            foreach ($stmtMetierRates->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $idMetier = (int) ($row['id_metier'] ?? 0);
                $totalCorrect = (int) ($row['total_correct'] ?? 0);
                $totalAnswers = (int) ($row['total_answers'] ?? 0);

                if ($totalAnswers <= 0) {
                    continue;
                }

                $metierSuccessLabels[] = $this->getMetierLabel($idMetier);
                $metierSuccessRates[] = round(($totalCorrect / $totalAnswers) * 100, 2);
            }
        } catch (PDOException $e) {
            $dbError = 'Erreur base de donnees: ' . $e->getMessage();
        }

        $this->renderStandalone('reponses/list', [
            'reponses' => $reponses,
            'totalReponses' => $totalReponses,
            'reponsesByQuestion' => $reponsesByQuestion,
            'rankingLabels' => $rankingLabels,
            'rankingRates' => $rankingRates,
            'rankingScores' => $rankingScores,
            'rankingLevels' => $rankingLevels,
            'rankingColors' => $rankingColors,
            'metierSuccessLabels' => $metierSuccessLabels,
            'metierSuccessRates' => $metierSuccessRates,
            'sort' => $sort,
            'searchTexte' => $searchTexte,
            'searchEstCorrecte' => $searchEstCorrecte,
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
            $idQuestionInput = trim((string) ($_POST['id_question'] ?? ''));
            $validation = $this->validateInput($texte, $idQuestionInput);
            $errors = $validation['errors'];
            $idQuestion = $validation['idQuestion'];

            if ($errors === [] && $idQuestion !== null) {
                try {
                    $pdo = $this->getConnection();
                    $stmt = $pdo->prepare('UPDATE reponses SET texte = ?, est_correcte = ?, id_question = ? WHERE id = ?');
                    $stmt->execute([$texte, $estCorrecte ? 1 : 0, $idQuestion, $id]);

                    $this->redirect('evaluation&msg=reponse_updated');
                } catch (PDOException $e) {
                    $dbError = 'Erreur base de donnees: ' . $e->getMessage();
                }
            }

            $reponse = new Reponse($id, $texte, $estCorrecte, $idQuestion ?? 0);
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
     * @return array{errors: array<int, string>, idQuestion: ?int}
     */
    private function validateInput(string $texte, string $idQuestionInput): array
    {
        $errors = [];
        $idQuestion = null;

        if ($texte === '') {
            $errors[] = 'La reponse ne doit pas etre vide.';
        }

        if ($idQuestionInput === '') {
            $errors[] = 'id_question ne doit pas etre vide.';
        } else {
            $validatedId = filter_var($idQuestionInput, FILTER_VALIDATE_INT);

            if ($validatedId === false || (int) $validatedId <= 0) {
                $errors[] = 'id_question doit etre un entier valide.';
            } else {
                $idQuestion = (int) $validatedId;
            }
        }

        return [
            'errors' => $errors,
            'idQuestion' => $idQuestion,
        ];
    }

    private function resolveSort(string $sort): string
    {
        $normalizedSort = strtolower(trim($sort));
        $allowedSorts = ['id_asc', 'id_desc', 'id_question_asc', 'id_question_desc'];

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
            'id_question_asc' => 'id_question ASC, id ASC',
            'id_question_desc' => 'id_question DESC, id DESC',
        ];

        return $map[$sort] ?? $map['id_desc'];
    }

    private function ensureUserAnswersTable(PDO $pdo): void
    {
        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS reponses_utilisateurs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_question INT NOT NULL,
                id_reponse INT NOT NULL,
                attempt_token VARCHAR(64) NOT NULL DEFAULT '',
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
        );

        $stmtColumn = $pdo->query("SHOW COLUMNS FROM reponses_utilisateurs LIKE 'attempt_token'");
        $hasAttemptToken = $stmtColumn !== false && $stmtColumn->fetch(PDO::FETCH_ASSOC) !== false;

        if (!$hasAttemptToken) {
            $pdo->exec(
                "ALTER TABLE reponses_utilisateurs
                 ADD COLUMN attempt_token VARCHAR(64) NOT NULL DEFAULT '' AFTER id_reponse,
                 ADD KEY idx_rep_user_attempt (attempt_token)"
            );
        }
    }

    private function getMetierLabel(int $idMetier): string
    {
        $labels = [
            1 => 'Metier 1 - Medecine',
            2 => 'Metier 2 - Mecanique',
            3 => 'Metier 3 - Developpement informatique',
        ];

        return $labels[$idMetier] ?? ('Metier ' . $idMetier);
    }

    private function resolveNiveau(int $score): string
    {
        if ($score >= 3) {
            return 'Expert';
        }

        if ($score === 2) {
            return 'Intermediaire';
        }

        return 'Debutant';
    }

    private function resolveNiveauColor(string $level): string
    {
        if ($level === 'Expert') {
            return '#28a745';
        }

        if ($level === 'Intermediaire') {
            return '#fd7e14';
        }

        return '#dc3545';
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
