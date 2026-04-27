<?php
declare(strict_types=1);

final class Post
{
    public static function findById(PDO $pdo, int $id): ?array
    {
        $st = $pdo->prepare('SELECT * FROM post WHERE id = :id LIMIT 1');
        $st->execute([':id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function listActive(PDO $pdo, string $q = ''): array
    {
        $q = trim($q);
        if ($q === '') {
            $st = $pdo->query("SELECT * FROM post WHERE status = 'active' ORDER BY created_at DESC, id DESC");
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }

        $st = $pdo->prepare(
            "SELECT * FROM post
             WHERE status = 'active'
               AND (title LIKE :q OR body LIKE :q OR flair LIKE :q OR author_type LIKE :q)
             ORDER BY created_at DESC, id DESC"
        );
        $st->execute([':q' => '%' . $q . '%']);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(PDO $pdo, array $data): int
    {
        $st = $pdo->prepare(
            "INSERT INTO post
              (title, body, photo_url, created_at, status, flair, upvote_count, author_type, min_upvotes_for_challenge)
             VALUES
              (:title, :body, :photo_url, :created_at, :status, :flair, :upvote_count, :author_type, :min_upvotes_for_challenge)"
        );
        $st->execute([
            ':title' => $data['title'],
            ':body' => $data['body'],
            ':photo_url' => $data['photo_url'] ?? null,
            ':created_at' => $data['created_at'],
            ':status' => $data['status'],
            ':flair' => $data['flair'],
            ':upvote_count' => (int)($data['upvote_count'] ?? 0),
            ':author_type' => $data['author_type'],
            ':min_upvotes_for_challenge' => (int)($data['min_upvotes_for_challenge'] ?? 0),
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function bumpUpvote(PDO $pdo, int $id, int $delta = 1): void
    {
        $st = $pdo->prepare('UPDATE post SET upvote_count = GREATEST(0, upvote_count + :d) WHERE id = :id');
        $st->execute([':id' => $id, ':d' => $delta]);
    }
}

