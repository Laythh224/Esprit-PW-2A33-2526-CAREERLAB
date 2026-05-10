<?php
/**
 * Connexion PDO réutilisable (à inclure depuis vos scripts API).
 *
 * Usage :
 *   require __DIR__ . '/db.php';
 *   $pdo = career_lab_pdo();
 */

declare(strict_types=1);

function career_lab_pdo(): PDO
{
    $configPath = __DIR__ . '/config.php';
    if (!is_readable($configPath)) {
        throw new RuntimeException(
            'Créez database/config.php à partir de database/config.example.php'
        );
    }
    /** @var array $cfg */
    $cfg = require $configPath;
    $db = $cfg['db'];
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $db['host'],
        $db['port'],
        $db['name'],
        $db['charset']
    );
    $pdo = new PDO($dsn, $db['user'], $db['pass'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}
