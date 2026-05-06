<?php

class Metier
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        $sql = 'SELECT id, title, description, salaire, competences
                FROM job
                ORDER BY id DESC';

        $statement = $this->connection->query($sql);

        return $statement->fetchAll();
    }
}
