<?php

class AuthRepository extends AbstractPdoRepository
{
    private const ALLOWED_TABLES = ['utilisateur', 'formateur', 'entreprise'];

    public function findByEmail(string $table, string $email): ?array
    {
        $this->assertAllowedTable($table);

        $stmt = $this->prepareOrFail("SELECT * FROM {$table} WHERE email = ? LIMIT 1");
        $this->executeStatementOrFail($stmt, [$email]);
        $result = $stmt->fetch();

        return $result !== false ? $result : null;
    }

    public function countAll(string $table): int
    {
        $this->assertAllowedTable($table);

        $stmt = $this->queryOrFail("SELECT COUNT(*) AS total FROM {$table}");
        $value = $stmt->fetchColumn();

        return (int) ($value !== false ? $value : 0);
    }

    private function assertAllowedTable(string $table): void
    {
        if (!in_array($table, self::ALLOWED_TABLES, true)) {
            throw new InvalidArgumentException('Table non autorisée.');
        }
    }
}
