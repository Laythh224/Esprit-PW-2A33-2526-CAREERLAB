<?php
declare(strict_types=1);

final class Challenge
{
    /**
     * Auto-status:
     * - upcoming until attached post reaches min_upvotes_for_challenge
     * - active once eligible
     * - closed stays closed
     */
    public static function syncStatusFromPost(PDO $pdo, int $challengeId): void
    {
        $st = $pdo->prepare(
            "SELECT c.id, c.status, p.upvote_count, p.min_upvotes_for_challenge
             FROM challenge c
             JOIN post p ON p.id = c.post_id
             WHERE c.id = :id
             LIMIT 1"
        );
        $st->execute([':id' => $challengeId]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        if (!$row) return;

        $current = (string)$row['status'];
        if ($current === 'closed') return;

        $up = (int)$row['upvote_count'];
        $min = (int)$row['min_upvotes_for_challenge'];
        $next = ($up >= $min) ? 'active' : 'upcoming';

        if ($next !== $current) {
            $u = $pdo->prepare("UPDATE challenge SET status = :s WHERE id = :id");
            $u->execute([':s' => $next, ':id' => $challengeId]);
        }
    }

    public static function listVisibleWithPost(PDO $pdo, string $q = ''): array
    {
        $q = trim($q);

        // Sync statuses cheaply in one SQL pass (excluding closed)
        $pdo->exec(
            "UPDATE challenge c
             JOIN post p ON p.id = c.post_id
             SET c.status = IF(p.upvote_count >= p.min_upvotes_for_challenge, 'active', 'upcoming')
             WHERE c.status <> 'closed'"
        );

        if ($q === '') {
            $st = $pdo->query(
                "SELECT
                   c.*,
                   p.title AS post_title,
                   p.upvote_count AS post_upvotes,
                   p.min_upvotes_for_challenge AS post_min_for_challenge
                 FROM challenge c
                 JOIN post p ON p.id = c.post_id
                 WHERE c.status IN ('active','upcoming')
                 ORDER BY c.start_date DESC, c.id DESC"
            );
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }

        $st = $pdo->prepare(
            "SELECT
               c.*,
               p.title AS post_title,
               p.upvote_count AS post_upvotes,
               p.min_upvotes_for_challenge AS post_min_for_challenge
             FROM challenge c
             JOIN post p ON p.id = c.post_id
             WHERE c.status IN ('active','upcoming')
               AND (
                 c.theme LIKE :q OR c.description LIKE :q OR c.flair LIKE :q OR c.creator_type LIKE :q
                 OR p.title LIKE :q
               )
             ORDER BY c.start_date DESC, c.id DESC"
        );
        $st->execute([':q' => '%' . $q . '%']);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByIdWithPost(PDO $pdo, int $id): ?array
    {
        self::syncStatusFromPost($pdo, $id);
        $st = $pdo->prepare(
            "SELECT
               c.*,
               p.title AS post_title,
               p.upvote_count AS post_upvotes,
               p.min_upvotes_for_challenge AS post_min_for_challenge
             FROM challenge c
             JOIN post p ON p.id = c.post_id
             WHERE c.id = :id
             LIMIT 1"
        );
        $st->execute([':id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function create(PDO $pdo, array $data): int
    {
        // Auto status from post eligibility
        $pst = $pdo->prepare("SELECT upvote_count, min_upvotes_for_challenge FROM post WHERE id = :id LIMIT 1");
        $pst->execute([':id' => (int)$data['post_id']]);
        $p = $pst->fetch(PDO::FETCH_ASSOC);
        if (!$p) {
            throw new RuntimeException('Post not found for challenge.');
        }
        $autoStatus = ((int)$p['upvote_count'] >= (int)$p['min_upvotes_for_challenge']) ? 'active' : 'upcoming';

        $st = $pdo->prepare(
            "INSERT INTO challenge
              (post_id, theme, description, flair, creator_type, start_date, end_date, status, reward_type, reward_title, reward_description)
             VALUES
              (:post_id, :theme, :description, :flair, :creator_type, :start_date, :end_date, :status, :reward_type, :reward_title, :reward_description)"
        );
        $st->execute([
            ':post_id' => (int)$data['post_id'],
            ':theme' => $data['theme'],
            ':description' => $data['description'],
            ':flair' => $data['flair'],
            ':creator_type' => $data['creator_type'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':status' => $autoStatus,
            ':reward_type' => $data['reward_type'],
            ':reward_title' => $data['reward_title'],
            ':reward_description' => $data['reward_description'],
        ]);
        return (int)$pdo->lastInsertId();
    }
}

