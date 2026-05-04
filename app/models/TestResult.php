<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

/**
 * TestResult Model - Manages test result storage and retrieval
 */
final class TestResult
{
    private int $id;
    private string $token;
    private ?string $testToken;
    private int $score;
    private int $totalQuestions;
    private array $resultDetails;
    private string $createdAt;
    private ?string $expiresAt;

    public function __construct(
        string $token,
        int $score,
        int $totalQuestions,
        array $resultDetails,
        ?string $testToken = null,
        ?string $expiresAt = null
    ) {
        $this->token = $token;
        $this->testToken = $testToken;
        $this->score = $score;
        $this->totalQuestions = $totalQuestions;
        $this->resultDetails = $resultDetails;
        $this->expiresAt = $expiresAt;
        $this->createdAt = (new \DateTime())->format('Y-m-d H:i:s');
    }

    /**
     * Save test result to database
     */
    public function save(PDO $pdo): bool
    {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO test_results (token, test_token, score, total_questions, result_details, created_at, expires_at) 
                 VALUES (:token, :test_token, :score, :total_questions, :result_details, :created_at, :expires_at)'
            );

            return $stmt->execute([
                ':token' => $this->token,
                ':test_token' => $this->testToken,
                ':score' => $this->score,
                ':total_questions' => $this->totalQuestions,
                ':result_details' => json_encode($this->resultDetails, JSON_UNESCAPED_UNICODE),
                ':created_at' => $this->createdAt,
                ':expires_at' => $this->expiresAt,
            ]);
        } catch (PDOException $e) {
            throw new PDOException('Error saving test result: ' . $e->getMessage());
        }
    }

    /**
     * Retrieve result by token
     */
    public static function findByToken(PDO $pdo, string $token): ?self
    {
        try {
            $stmt = $pdo->prepare(
                'SELECT id, token, test_token, score, total_questions, result_details, created_at, expires_at 
                 FROM test_results 
                 WHERE token = :token'
            );

            $stmt->execute([':token' => $token]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return null;
            }

            // Check if result has expired
            if ($row['expires_at'] !== null) {
                $expiresAt = new \DateTime($row['expires_at']);
                if ($expiresAt < new \DateTime()) {
                    return null; // Result has expired
                }
            }

            $result = new self(
                $row['token'],
                (int) $row['score'],
                (int) $row['total_questions'],
                json_decode($row['result_details'], true) ?? [],
                $row['test_token'],
                $row['expires_at']
            );

            $result->id = (int) $row['id'];
            return $result;
        } catch (PDOException $e) {
            throw new PDOException('Error retrieving test result: ' . $e->getMessage());
        }
    }

    // Getters
    public function getId(): int
    {
        return $this->id ?? 0;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getTestToken(): ?string
    {
        return $this->testToken;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getTotalQuestions(): int
    {
        return $this->totalQuestions;
    }

    public function getResultDetails(): array
    {
        return $this->resultDetails;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getExpiresAt(): ?string
    {
        return $this->expiresAt;
    }

    public function getPercentage(): float
    {
        if ($this->totalQuestions === 0) {
            return 0;
        }
        return round(($this->score / $this->totalQuestions) * 100, 2);
    }
}
