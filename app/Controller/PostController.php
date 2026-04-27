<?php
declare(strict_types=1);

final class PostController
{
    public static function index(PDO $pdo): void
    {
        $q = isset($_GET['q']) ? (string)$_GET['q'] : '';
        $posts = Post::listActive($pdo, $q);
        require __DIR__ . '/../Views/post/index.php';
    }

    public static function show(PDO $pdo): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $post = $id ? Post::findById($pdo, $id) : null;
        require __DIR__ . '/../Views/post/show.php';
    }

    public static function create(PDO $pdo): void
    {
        $errors = [];
        $values = [
            'title' => '',
            'body' => '',
            'photo_url' => '',
            'created_at' => date('Y-m-d'),
            'status' => 'active',
            'flair' => 'Question',
            'author_type' => 'Utilisateur',
            'min_upvotes_for_challenge' => '0',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($values as $k => $_) {
                $values[$k] = isset($_POST[$k]) ? trim((string)$_POST[$k]) : $values[$k];
            }

            if (mb_strlen($values['title']) < 10 || mb_strlen($values['title']) > 200) {
                $errors['title'] = 'Title must be between 10 and 200 characters.';
            }
            if (mb_strlen($values['body']) < 10 || mb_strlen($values['body']) > 5000) {
                $errors['body'] = 'Body must be between 10 and 5000 characters.';
            }
            if (!in_array($values['flair'], ['Question','Thread','Disclaimer'], true)) {
                $errors['flair'] = 'Invalid flair.';
            }
            if (!in_array($values['status'], ['active','locked','deleted'], true)) {
                $errors['status'] = 'Invalid status.';
            }
            if (!in_array($values['author_type'], ['Utilisateur','Formateur','Entreprise'], true)) {
                $errors['author_type'] = 'Invalid author type.';
            }

            $min = (int)$values['min_upvotes_for_challenge'];
            if (($values['author_type'] === 'Formateur' || $values['author_type'] === 'Entreprise') && $min < 0) {
                $errors['min_upvotes_for_challenge'] = 'Minimum upvotes must be >= 0.';
            }
            if ($values['author_type'] === 'Utilisateur') {
                $values['min_upvotes_for_challenge'] = '0';
            }

            if (!$errors) {
                $id = Post::create($pdo, [
                    'title' => $values['title'],
                    'body' => $values['body'],
                    'photo_url' => $values['photo_url'] !== '' ? $values['photo_url'] : null,
                    'created_at' => $values['created_at'],
                    'status' => $values['status'],
                    'flair' => $values['flair'],
                    'author_type' => $values['author_type'],
                    'min_upvotes_for_challenge' => (int)$values['min_upvotes_for_challenge'],
                ]);
                header('Location: index.php?c=post&a=show&id=' . $id);
                exit;
            }
        }

        require __DIR__ . '/../Views/post/create.php';
    }

    public static function upvote(PDO $pdo): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            Post::bumpUpvote($pdo, $id, 1);
        }
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php?c=post&a=index'));
        exit;
    }
}

