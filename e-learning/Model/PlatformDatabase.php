<?php

declare(strict_types=1);

namespace App\Model;

use PDO;
use PDOException;

final class PlatformDatabase
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $configPath = __DIR__ . '/config/platform_database.php';
        if (!is_file($configPath)) {
            http_response_code(500);
            echo 'Configuration plateforme introuvable (platform_database.php).';
            exit;
        }

        $config = require $configPath;
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['dbname'],
            $config['charset'] ?? 'utf8mb4'
        );

        try {
            self::$connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            http_response_code(500);
            echo 'Connexion base plateforme impossible : ' . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
            exit;
        }

        return self::$connection;
    }
}
