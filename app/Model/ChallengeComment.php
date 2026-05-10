<?php
declare(strict_types=1);

final class ChallengeComment
{
    public static function listByChallengeId(PDO $pdo, int $challengeId): array
    {
        $st = $pdo->prepare(
            "SELECT id, challenge_id, body, image_url, video_url, created_at, upvote_count
             FROM challenge_comment
             WHERE challenge_id = :challenge_id
             ORDER BY created_at DESC, id DESC"
        );
        $st->execute([':challenge_id' => $challengeId]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(PDO $pdo, array $data): int
    {
        $st = $pdo->prepare(
            "INSERT INTO challenge_comment
              (challenge_id, body, image_url, video_url)
             VALUES
              (:challenge_id, :body, :image_url, :video_url)"
        );
        $st->execute([
            ':challenge_id' => (int)$data['challenge_id'],
            ':body' => (string)$data['body'],
            ':image_url' => $data['image_url'] !== '' ? (string)$data['image_url'] : null,
            ':video_url' => $data['video_url'] !== '' ? (string)$data['video_url'] : null,
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function bumpUpvote(PDO $pdo, int $commentId, int $delta = 1): void
    {
        $st = $pdo->prepare(
            "UPDATE challenge_comment
             SET upvote_count = GREATEST(0, upvote_count + :d)
             WHERE id = :id"
        );
        $st->execute([
            ':id' => $commentId,
            ':d' => $delta,
        ]);
    }

    public static function findById(PDO $pdo, int $commentId): ?array
    {
        $st = $pdo->prepare(
            "SELECT id, challenge_id, body, image_url, video_url, created_at, upvote_count
             FROM challenge_comment
             WHERE id = :id
             LIMIT 1"
        );
        $st->execute([':id' => $commentId]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}

