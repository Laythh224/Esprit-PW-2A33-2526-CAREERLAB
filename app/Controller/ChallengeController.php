<?php
declare(strict_types=1);

final class ChallengeController
{
    private static function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private static function validateUrlOptional(string $url): bool
    {
        if ($url === '') return true;
        return (bool)filter_var($url, FILTER_VALIDATE_URL);
    }

    public static function index(PDO $pdo): void
    {
        $q = isset($_GET['q']) ? (string)$_GET['q'] : '';
        $challenges = Challenge::listVisibleWithPost($pdo, $q);
        require __DIR__ . '/../Views/challenge/index.php';
    }

    public static function show(PDO $pdo): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $challenge = $id ? Challenge::findByIdWithPost($pdo, $id) : null;
        require __DIR__ . '/../Views/challenge/show.php';
    }

    public static function create(PDO $pdo): void
    {
        $errors = [];
        $values = [
            'post_id' => '',
            'theme' => '',
            'description' => '',
            'flair' => 'Projet',
            'creator_type' => 'Formateur',
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d'),
            'reward_type' => 'job',
            'reward_title' => '',
            'reward_description' => '',
        ];

        // Use existing active posts for attaching (you can change this if needed)
        $posts = Post::listActive($pdo, '');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($values as $k => $_) {
                $values[$k] = isset($_POST[$k]) ? trim((string)$_POST[$k]) : $values[$k];
            }

            $postId = (int)$values['post_id'];
            if ($postId <= 0) {
                $errors['post_id'] = 'Select a post.';
            } else {
                $p = Post::findById($pdo, $postId);
                if (!$p) $errors['post_id'] = 'Post not found.';
            }

            if (mb_strlen($values['theme']) < 10 || mb_strlen($values['theme']) > 200) {
                $errors['theme'] = 'Theme must be between 10 and 200 characters.';
            }
            if (mb_strlen($values['description']) < 10 || mb_strlen($values['description']) > 5000) {
                $errors['description'] = 'Description must be between 10 and 5000 characters.';
            }
            if (!in_array($values['flair'], ['Projet','Showcast','Débat','Pitch'], true)) {
                $errors['flair'] = 'Invalid flair.';
            }
            if (!in_array($values['creator_type'], ['Formateur','Entreprise'], true)) {
                $errors['creator_type'] = 'Invalid creator type.';
            }
            if (!$values['start_date']) $errors['start_date'] = 'Start date is required.';
            if (!$values['end_date']) $errors['end_date'] = 'End date is required.';
            if ($values['start_date'] && $values['end_date'] && $values['end_date'] <= $values['start_date']) {
                $errors['end_date'] = 'End date must be after start date.';
            }
            if (!in_array($values['reward_type'], ['job','course'], true)) {
                $errors['reward_type'] = 'Invalid reward type.';
            }
            if (mb_strlen($values['reward_title']) < 10 || mb_strlen($values['reward_title']) > 200) {
                $errors['reward_title'] = 'Reward title must be between 10 and 200 characters.';
            }
            if (mb_strlen($values['reward_description']) < 10 || mb_strlen($values['reward_description']) > 5000) {
                $errors['reward_description'] = 'Reward description must be between 10 and 5000 characters.';
            }

            if (!$errors) {
                $id = Challenge::create($pdo, [
                    'post_id' => $postId,
                    'theme' => $values['theme'],
                    'description' => $values['description'],
                    'flair' => $values['flair'],
                    'creator_type' => $values['creator_type'],
                    'start_date' => $values['start_date'],
                    'end_date' => $values['end_date'],
                    'reward_type' => $values['reward_type'],
                    'reward_title' => $values['reward_title'],
                    'reward_description' => $values['reward_description'],
                ]);
                header('Location: index.php?c=challenge&a=show&id=' . $id);
                exit;
            }
        }

        require __DIR__ . '/../Views/challenge/create.php';
    }

    public static function comments(PDO $pdo): void
    {
        try {
            $challengeId = (int)($_GET['challenge_id'] ?? 0);
            if ($challengeId <= 0) {
                self::json(['ok' => false, 'message' => 'challenge_id invalide.'], 422);
                return;
            }

            $challenge = Challenge::findByIdWithPost($pdo, $challengeId);
            if (!$challenge) {
                self::json(['ok' => false, 'message' => 'Défi introuvable.'], 404);
                return;
            }

            $items = ChallengeComment::listByChallengeId($pdo, $challengeId);
            self::json(['ok' => true, 'items' => $items]);
        } catch (Throwable $e) {
            self::json(['ok' => false, 'message' => 'Erreur serveur lors du chargement des commentaires.'], 500);
        }
    }

    public static function commentCreate(PDO $pdo): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            self::json(['ok' => false, 'message' => 'Méthode non autorisée.'], 405);
            return;
        }
        try {
            $challengeId = (int)($_POST['challenge_id'] ?? 0);
            $body = trim((string)($_POST['body'] ?? ''));
            $imageUrl = trim((string)($_POST['image_url'] ?? ''));
            $videoUrl = trim((string)($_POST['video_url'] ?? ''));

            if ($challengeId <= 0) {
                self::json(['ok' => false, 'message' => 'challenge_id invalide.'], 422);
                return;
            }
            $challenge = Challenge::findByIdWithPost($pdo, $challengeId);
            if (!$challenge) {
                self::json(['ok' => false, 'message' => 'Défi introuvable.'], 404);
                return;
            }
            if ((string)$challenge['status'] !== 'active') {
                self::json(['ok' => false, 'message' => 'Les commentaires sont disponibles uniquement pour les défis actifs.'], 422);
                return;
            }

            $wordCount = preg_match_all('/\S+/u', $body);
            if (!$wordCount || $wordCount < 10 || $wordCount > 500) {
                self::json(['ok' => false, 'message' => 'Le texte doit contenir entre 10 et 500 mots.'], 422);
                return;
            }
            if (!self::validateUrlOptional($imageUrl)) {
                self::json(['ok' => false, 'message' => "L'URL de l'image n'est pas valide."], 422);
                return;
            }
            if (!self::validateUrlOptional($videoUrl)) {
                self::json(['ok' => false, 'message' => "L'URL de la vidéo n'est pas valide."], 422);
                return;
            }

            $id = ChallengeComment::create($pdo, [
                'challenge_id' => $challengeId,
                'body' => $body,
                'image_url' => $imageUrl,
                'video_url' => $videoUrl,
            ]);
            $item = ChallengeComment::findById($pdo, $id);
            self::json(['ok' => true, 'item' => $item], 201);
        } catch (Throwable $e) {
            self::json(['ok' => false, 'message' => "Erreur serveur lors de l'enregistrement du commentaire."], 500);
        }
    }

    public static function commentUpvote(PDO $pdo): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            self::json(['ok' => false, 'message' => 'Méthode non autorisée.'], 405);
            return;
        }
        try {
            $commentId = (int)($_POST['comment_id'] ?? 0);
            if ($commentId <= 0) {
                self::json(['ok' => false, 'message' => 'comment_id invalide.'], 422);
                return;
            }

            $item = ChallengeComment::findById($pdo, $commentId);
            if (!$item) {
                self::json(['ok' => false, 'message' => 'Commentaire introuvable.'], 404);
                return;
            }

            ChallengeComment::bumpUpvote($pdo, $commentId, 1);
            $updated = ChallengeComment::findById($pdo, $commentId);
            self::json(['ok' => true, 'item' => $updated]);
        } catch (Throwable $e) {
            self::json(['ok' => false, 'message' => "Erreur serveur lors du vote du commentaire."], 500);
        }
    }
}

