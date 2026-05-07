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
            $sessionExists = false;

            $formationCheck = $connection->query("SHOW TABLES LIKE 'formation'");
            if ($formationCheck !== false && $formationCheck->fetch() !== false) {
                $formationExists = true;
            }

            $sessionCheck = $connection->query("SHOW TABLES LIKE 'session'");
            if ($sessionCheck !== false && $sessionCheck->fetch() !== false) {
                $sessionExists = true;
            }

            $critereExists = false;
            $critereCheck = $connection->query("SHOW TABLES LIKE 'critere'");
            if ($critereCheck !== false && $critereCheck->fetch() !== false) {
                $critereExists = true;
            }

            if (!$sessionExists && $critereExists) {
                $connection->exec('RENAME TABLE `critere` TO `session`');
                $sessionExists = true;
            }

            if (!$formationExists) {
                $connection->exec(
                    "CREATE TABLE formation (
                        nom_formation VARCHAR(150) NOT NULL,
                        specialite VARCHAR(150) NOT NULL,
                        description TEXT NOT NULL,
                        niveau VARCHAR(80) NOT NULL,
                        PRIMARY KEY (nom_formation)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
                );
            }

            if ($sessionExists) {
                self::ensureSessionDateColumns($connection);
                self::ensureSessionNbPlaceColumn($connection);
            }

            if (!$sessionExists) {
                $connection->exec(
                    "CREATE TABLE `session` (
                        id INT NOT NULL AUTO_INCREMENT,
                        nom_formation VARCHAR(150) NOT NULL,
                        type ENUM('online', 'presentiel') NOT NULL,
                        lien VARCHAR(255) DEFAULT NULL,
                        duree_online INT DEFAULT NULL,
                        adresse VARCHAR(255) DEFAULT NULL,
                        salle VARCHAR(120) DEFAULT NULL,
                        duree_presentiel INT DEFAULT NULL,
                        date_debut DATE NOT NULL,
                        date_fin DATE NOT NULL,
                        nb_place INT NOT NULL DEFAULT 1,
                        PRIMARY KEY (id),
                        INDEX idx_session_formation (nom_formation),
                        CONSTRAINT fk_session_formation
                            FOREIGN KEY (nom_formation) REFERENCES formation(nom_formation)
                            ON DELETE CASCADE ON UPDATE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
                );
            }

            try {
                self::migrateFormationNbPlaceToSession($connection);
            } catch (\Throwable $exception) {
                // Migration nb_place ; peut necessiter execution manuelle du script SQL.
            }

            $clientCheck = $connection->query("SHOW TABLES LIKE 'client'");
            if ($clientCheck !== false && $clientCheck->fetch() === false) {
                $connection->exec(
                    "CREATE TABLE client (
                        cin VARCHAR(8) NOT NULL,
                        nom VARCHAR(80) NOT NULL,
                        prenom VARCHAR(80) NOT NULL,
                        adresse VARCHAR(200) NOT NULL,
                        niveau VARCHAR(80) NOT NULL,
                        age TINYINT UNSIGNED NOT NULL,
                        tel VARCHAR(8) NOT NULL,
                        nom_formation VARCHAR(150) DEFAULT NULL,
                        session_id INT DEFAULT NULL,
                        PRIMARY KEY (cin)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
                );
            }

            try {
                self::ensureClientNomFormationColumn($connection);
            } catch (\Throwable $exception) {
                // Migration nom_formation client.
            }

            try {
                self::ensureClientSessionIdColumn($connection);
            } catch (\Throwable $exception) {
                // Migration session_id client.
            }
        } catch (\Throwable $exception) {
            // Keep application running; controllers will surface useful errors if needed.
        }
    }

    private static function ensureSessionDateColumns(PDO $connection): void
    {
        $statement = $connection->query(
            "SELECT COUNT(*) AS c FROM information_schema.columns
             WHERE table_schema = DATABASE() AND table_name = 'session' AND column_name = 'date_debut'"
        );
        if ($statement === false) {
            return;
        }
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ((int) ($row['c'] ?? 0) > 0) {
            return;
        }

        $connection->exec(
            "ALTER TABLE `session`
             ADD COLUMN date_debut DATE NOT NULL DEFAULT '1970-01-01',
             ADD COLUMN date_fin DATE NOT NULL DEFAULT '1970-01-02'"
        );
    }

    private static function ensureClientNomFormationColumn(PDO $connection): void
    {
        $clientCheck = $connection->query("SHOW TABLES LIKE 'client'");
        if ($clientCheck === false || $clientCheck->fetch() === false) {
            return;
        }

        $statement = $connection->query(
            "SELECT COUNT(*) AS c FROM information_schema.columns
             WHERE table_schema = DATABASE() AND table_name = 'client' AND column_name = 'nom_formation'"
        );
        if ($statement === false) {
            return;
        }
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ((int) ($row['c'] ?? 0) > 0) {
            return;
        }

        $connection->exec(
            'ALTER TABLE client ADD COLUMN nom_formation VARCHAR(150) DEFAULT NULL'
        );
    }

    private static function ensureClientSessionIdColumn(PDO $connection): void
    {
        $clientCheck = $connection->query("SHOW TABLES LIKE 'client'");
        if ($clientCheck === false || $clientCheck->fetch() === false) {
            return;
        }

        $statement = $connection->query(
            "SELECT COUNT(*) AS c FROM information_schema.columns
             WHERE table_schema = DATABASE() AND table_name = 'client' AND column_name = 'session_id'"
        );
        if ($statement === false) {
            return;
        }
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ((int) ($row['c'] ?? 0) > 0) {
            return;
        }

        $connection->exec(
            'ALTER TABLE client ADD COLUMN session_id INT DEFAULT NULL'
        );
    }

    private static function ensureSessionNbPlaceColumn(PDO $connection): void
    {
        $statement = $connection->query(
            "SELECT COUNT(*) AS c FROM information_schema.columns
             WHERE table_schema = DATABASE() AND table_name = 'session' AND column_name = 'nb_place'"
        );
        if ($statement === false) {
            return;
        }
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ((int) ($row['c'] ?? 0) > 0) {
            return;
        }

        $connection->exec('ALTER TABLE `session` ADD COLUMN nb_place INT NOT NULL DEFAULT 1');
    }

    /**
     * Ancienne colonne formation.nb_place -> session.nb_place puis suppression sur formation.
     */
    private static function migrateFormationNbPlaceToSession(PDO $connection): void
    {
        $formationCheck = $connection->query("SHOW TABLES LIKE 'formation'");
        if ($formationCheck === false || $formationCheck->fetch() === false) {
            return;
        }

        $statement = $connection->query(
            "SELECT COUNT(*) AS c FROM information_schema.columns
             WHERE table_schema = DATABASE() AND table_name = 'formation' AND column_name = 'nb_place'"
        );
        if ($statement === false) {
            return;
        }
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ((int) ($row['c'] ?? 0) === 0) {
            foreach (['date_debut', 'date_fin', 'duree'] as $column) {
                $colStmt = $connection->query(
                    "SELECT COUNT(*) AS c FROM information_schema.columns
                     WHERE table_schema = DATABASE() AND table_name = 'formation' AND column_name = " . $connection->quote($column)
                );
                if ($colStmt === false) {
                    continue;
                }
                $colRow = $colStmt->fetch(PDO::FETCH_ASSOC);
                if ((int) ($colRow['c'] ?? 0) > 0) {
                    $connection->exec('ALTER TABLE formation DROP COLUMN `' . str_replace('`', '``', $column) . '`');
                }
            }

            return;
        }

        $sessionCheck = $connection->query("SHOW TABLES LIKE 'session'");
        if ($sessionCheck !== false && $sessionCheck->fetch() !== false) {
            self::ensureSessionNbPlaceColumn($connection);
            $connection->exec(
                'UPDATE `session` s INNER JOIN formation f ON s.nom_formation = f.nom_formation SET s.nb_place = f.nb_place'
            );
        }

        $connection->exec('ALTER TABLE formation DROP COLUMN nb_place');

        foreach (['date_debut', 'date_fin', 'duree'] as $column) {
            $colStmt = $connection->query(
                "SELECT COUNT(*) AS c FROM information_schema.columns
                 WHERE table_schema = DATABASE() AND table_name = 'formation' AND column_name = " . $connection->quote($column)
            );
            if ($colStmt === false) {
                continue;
            }
            $colRow = $colStmt->fetch(PDO::FETCH_ASSOC);
            if ((int) ($colRow['c'] ?? 0) > 0) {
                $connection->exec('ALTER TABLE formation DROP COLUMN `' . str_replace('`', '``', $column) . '`');
            }
        }
    }
}
