<?php

class PasswordResetModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function findAccountByEmail(string $email): ?array
    {
        $queries = [
            ['table' => 'utilisateur', 'label' => 'utilisateur', 'name' => "TRIM(CONCAT(COALESCE(prenom, ''), ' ', COALESCE(nom, '')))"],
            ['table' => 'formateur', 'label' => 'formateur', 'name' => "TRIM(CONCAT(COALESCE(prenom, ''), ' ', COALESCE(nom, '')))"],
            ['table' => 'entreprise', 'label' => 'entreprise', 'name' => 'nom_entreprise'],
        ];

        foreach ($queries as $query) {
            $sql = "SELECT id, email, {$query['name']} AS display_name FROM {$query['table']} WHERE email = ? LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$email]);
            $row = $stmt->fetch();

            if ($row !== false) {
                return [
                    'id' => (int) $row['id'],
                    'email' => (string) $row['email'],
                    'role' => $query['label'],
                    'display_name' => trim((string) ($row['display_name'] ?? '')) ?: (string) $row['email'],
                ];
            }
        }

        return null;
    }

    public function findResetByEmail(string $email): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM reset_password WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    public function upsertResetRequest(string $email, string $codeHash, string $expiresAt, string $lastSentAt): void
    {
        $existing = $this->findResetByEmail($email);

        if ($existing === null) {
            $stmt = $this->conn->prepare(
                'INSERT INTO reset_password (email, code_hash, expires_at, attempts, resend_count, last_sent_at, is_verified, created_at, updated_at)
                 VALUES (?, ?, ?, 0, 0, ?, 0, NOW(), NOW())'
            );
            $stmt->execute([$email, $codeHash, $expiresAt, $lastSentAt]);
            return;
        }

        $stmt = $this->conn->prepare(
            'UPDATE reset_password
             SET code_hash = ?, expires_at = ?, attempts = 0, last_sent_at = ?, is_verified = 0, verified_at = NULL, updated_at = NOW()
             WHERE email = ?'
        );
        $stmt->execute([$codeHash, $expiresAt, $lastSentAt, $email]);
    }

    public function markResent(string $email, string $codeHash, string $expiresAt, string $lastSentAt): void
    {
        $stmt = $this->conn->prepare(
            'UPDATE reset_password
             SET code_hash = ?, expires_at = ?, attempts = 0, resend_count = resend_count + 1, last_sent_at = ?, is_verified = 0, verified_at = NULL, updated_at = NOW()
             WHERE email = ?'
        );
        $stmt->execute([$codeHash, $expiresAt, $lastSentAt, $email]);
    }

    public function incrementAttempts(string $email): void
    {
        $stmt = $this->conn->prepare('UPDATE reset_password SET attempts = attempts + 1, updated_at = NOW() WHERE email = ?');
        $stmt->execute([$email]);
    }

    public function markVerified(string $email): void
    {
        $stmt = $this->conn->prepare('UPDATE reset_password SET is_verified = 1, verified_at = NOW(), updated_at = NOW() WHERE email = ?');
        $stmt->execute([$email]);
    }

    public function deleteResetRequest(string $email): void
    {
        $stmt = $this->conn->prepare('DELETE FROM reset_password WHERE email = ?');
        $stmt->execute([$email]);
    }

    public function updatePasswordByEmail(string $email, string $passwordHash): bool
    {
        foreach (['utilisateur', 'formateur', 'entreprise'] as $table) {
            $stmt = $this->conn->prepare("UPDATE {$table} SET password = ? WHERE email = ?");
            $stmt->execute([$passwordHash, $email]);

            if ($stmt->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    public function purgeExpired(): void
    {
        $stmt = $this->conn->prepare('DELETE FROM reset_password WHERE expires_at < NOW() OR updated_at < DATE_SUB(NOW(), INTERVAL 1 DAY)');
        $stmt->execute();
    }
}
