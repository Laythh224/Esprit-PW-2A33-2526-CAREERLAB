<?php

declare(strict_types=1);

namespace App\Model;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;
    private static bool $schemaEnsured = false;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $configCandidates = [
            __DIR__ . '/config/database.php',
            __DIR__ . '/../config/database.php',
        ];

        $configPath = null;
        foreach ($configCandidates as $candidate) {
            if (is_file($candidate)) {
                $configPath = $candidate;
                break;
            }
        }

        if ($configPath === null) {
            http_response_code(500);
            echo 'Configuration base de donnees introuvable.';
            exit;
        }

        $config = require $configPath;
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['dbname'],
            $config['charset']
        );

        try {
            self::$connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            http_response_code(500);
            echo 'Connexion base de donnees impossible : ' . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
            exit;
        }

        self::ensureSchema(self::$connection);

        return self::$connection;
    }

    private static function ensureSchema(PDO $connection): void
    {
        if (self::$schemaEnsured) {
            return;
        }

        self::$schemaEnsured = true;

        try {
            $formationExists = false;
            $critereExists = false;

            $formationCheck = $connection->query("SHOW TABLES LIKE 'formation'");
            if ($formationCheck !== false && $formationCheck->fetch() !== false) {
                $formationExists = true;
            }

            $critereCheck = $connection->query("SHOW TABLES LIKE 'critere'");
            if ($critereCheck !== false && $critereCheck->fetch() !== false) {
                $critereExists = true;
            }

            if (!$formationExists) {
                $connection->exec(
                    "CREATE TABLE formation (
                        nom_formation VARCHAR(150) NOT NULL,
                        specialite VARCHAR(150) NOT NULL,
                        description TEXT NOT NULL,
                        date_debut DATE NOT NULL,
                        date_fin DATE NOT NULL,
                        niveau VARCHAR(80) NOT NULL,
                        duree INT NOT NULL,
                        PRIMARY KEY (nom_formation)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
                );
            }

            if (!$critereExists) {
                $connection->exec(
                    "CREATE TABLE critere (
                        id INT NOT NULL AUTO_INCREMENT,
                        nom_formation VARCHAR(150) NOT NULL,
                        type ENUM('online', 'presentiel') NOT NULL,
                        lien VARCHAR(255) DEFAULT NULL,
                        duree_online INT DEFAULT NULL,
                        adresse VARCHAR(255) DEFAULT NULL,
                        salle VARCHAR(120) DEFAULT NULL,
                        duree_presentiel INT DEFAULT NULL,
                        PRIMARY KEY (id),
                        INDEX idx_critere_formation (nom_formation),
                        CONSTRAINT fk_critere_formation
                            FOREIGN KEY (nom_formation) REFERENCES formation(nom_formation)
                            ON DELETE CASCADE ON UPDATE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
                );
            }
        } catch (\Throwable $exception) {
            // Keep application running; controllers will surface useful errors if needed.
        }
    }
}

