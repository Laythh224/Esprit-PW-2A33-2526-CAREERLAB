<?php

class Database
{
    private PDO $connection;

    public function __construct(
        string $host = 'localhost',
        string $user = 'root',
        string $password = '',
        string $database = 'plateforme_emploi'
    ) {
        if (!extension_loaded('pdo_mysql')) {
            throw new RuntimeException("L'extension pdo_mysql est requise.");
        }

        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $host, $database);
        $this->connection = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public function connection(): PDO
    {
        return $this->connection;
    }
}
