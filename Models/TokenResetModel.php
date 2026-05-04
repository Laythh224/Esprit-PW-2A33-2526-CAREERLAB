<?php

class TokenResetModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Trouver un compte par email dans toutes les tables d'utilisateurs
     */
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

    /**
     * Créer un nouveau token pour réinitialisation
     */
    public function createToken(string $email, string $token, string $expiresAt): bool
    {
        // Désactiver les anciens tokens
        $this->disableOldTokens($email);

        $stmt = $this->conn->prepare(
            'INSERT INTO reset_tokens (email, token, expires_at, is_used, created_at, updated_at)
             VALUES (?, ?, ?, 0, NOW(), NOW())'
        );

        return $stmt->execute([$email, $token, $expiresAt]);
    }

    /**
     * Trouver un token valide
     */
    public function findValidToken(string $token): ?array
    {
        $stmt = $this->conn->prepare(
            'SELECT * FROM reset_tokens 
             WHERE token = ? 
             AND is_used = 0 
             AND expires_at > NOW()
             LIMIT 1'
        );
        $stmt->execute([$token]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    /**
     * Obtenir les informations du token
     */
    public function getTokenInfo(string $token): ?array
    {
        $stmt = $this->conn->prepare(
            'SELECT id, email, token, expires_at, is_used, created_at FROM reset_tokens 
             WHERE token = ? 
             LIMIT 1'
        );
        $stmt->execute([$token]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        return [
            'id' => (int) $row['id'],
            'email' => (string) $row['email'],
            'token' => (string) $row['token'],
            'expires_at' => (string) $row['expires_at'],
            'is_used' => (int) $row['is_used'],
            'created_at' => (string) $row['created_at'],
        ];
    }

    /**
     * Marquer un token comme utilisé
     */
    public function markTokenAsUsed(string $token): bool
    {
        $stmt = $this->conn->prepare(
            'UPDATE reset_tokens 
             SET is_used = 1, used_at = NOW(), updated_at = NOW() 
             WHERE token = ?'
        );

        return $stmt->execute([$token]);
    }

    /**
     * Désactiver les anciens tokens d'un email
     */
    public function disableOldTokens(string $email): void
    {
        $stmt = $this->conn->prepare(
            'UPDATE reset_tokens 
             SET is_used = 1, updated_at = NOW() 
             WHERE email = ? AND is_used = 0'
        );
        $stmt->execute([$email]);
    }

    /**
     * Supprimer les tokens expirés
     */
    public function purgeExpiredTokens(): void
    {
        $stmt = $this->conn->prepare(
            'DELETE FROM reset_tokens 
             WHERE expires_at < NOW() OR (is_used = 1 AND used_at < DATE_SUB(NOW(), INTERVAL 24 HOUR))'
        );
        $stmt->execute();
    }

    /**
     * Vérifier le rate limiting
     */
    public function checkRateLimit(string $email, int $maxRequests = 5, int $windowSeconds = 3600): bool
    {
        // Nettoyer les anciennes tentatives
        $stmt = $this->conn->prepare(
            'DELETE FROM reset_tokens 
             WHERE email = ? AND last_request_at < DATE_SUB(NOW(), INTERVAL 1 HOUR)'
        );
        $stmt->execute([$email]);

        // Compter les demandes récentes
        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) as count FROM reset_tokens 
             WHERE email = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)'
        );
        $stmt->execute([$email]);
        $result = $stmt->fetch();

        return (int) $result['count'] < $maxRequests;
    }

    /**
     * Mettre à jour le mot de passe dans toutes les tables
     */
    public function updatePasswordByEmail(string $email, string $passwordHash): bool
    {
        $tables = ['utilisateur', 'formateur', 'entreprise'];
        $updated = false;

        foreach ($tables as $table) {
            $stmt = $this->conn->prepare(
                "UPDATE {$table} 
                 SET password = ? 
                 WHERE email = ?"
            );
            $stmt->execute([$passwordHash, $email]);

            if ($stmt->rowCount() > 0) {
                $updated = true;
            }
        }

        return $updated;
    }

    /**
     * Compter les tokens valides d'un email
     */
    public function countValidTokens(string $email): int
    {
        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) as count FROM reset_tokens 
             WHERE email = ? AND is_used = 0 AND expires_at > NOW()'
        );
        $stmt->execute([$email]);
        $result = $stmt->fetch();

        return (int) $result['count'];
    }
}
