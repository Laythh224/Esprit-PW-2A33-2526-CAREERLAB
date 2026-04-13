<?php

abstract class AbstractPdoRepository
{
    protected PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    protected function prepareOrFail(string $sql): PDOStatement
    {
        return $this->conn->prepare($sql);
    }

    protected function executeStatementOrFail(PDOStatement $stmt, array $params): void
    {
        $stmt->execute($params);
    }

    protected function queryOrFail(string $sql): PDOStatement
    {
        return $this->conn->query($sql);
    }
}
