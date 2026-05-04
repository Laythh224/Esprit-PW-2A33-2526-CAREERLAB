<?php

class AiSupportRequestModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create(string $name, string $email, string $message): int
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO ai_support_requests (name, email, message, request_type, ai_response, ai_status, created_at)
             VALUES (:name, :email, :message, :request_type, :ai_response, :ai_status, NOW())'
        );

        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':message' => $message,
            ':request_type' => 'pending',
            ':ai_response' => '',
            ':ai_status' => 'pending',
        ]);

        return (int) $this->conn->lastInsertId();
    }

    public function updateAiResponse(int $id, string $requestType, string $aiResponse, string $aiStatus): void
    {
        $stmt = $this->conn->prepare(
            'UPDATE ai_support_requests
             SET request_type = :request_type, ai_response = :ai_response, ai_status = :ai_status
             WHERE id = :id'
        );

        $stmt->execute([
            ':id' => $id,
            ':request_type' => $requestType,
            ':ai_response' => $aiResponse,
            ':ai_status' => $aiStatus,
        ]);
    }

    public function all(int $limit = 100): array
    {
        $limit = max(1, min($limit, 200));
        $stmt = $this->conn->prepare(
            'SELECT id, name, email, message, request_type, ai_response, ai_status, created_at
             FROM ai_support_requests
             ORDER BY created_at DESC, id DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
