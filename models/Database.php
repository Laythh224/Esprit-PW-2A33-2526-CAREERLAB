<?php

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    public function __construct(
        string $host = 'localhost',
        string $user = 'root',
        string $password = '',
        string $database = 'plateforme_unique'
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
        self::$instance = $this;
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function connection(): PDO
    {
        return $this->connection;
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}
