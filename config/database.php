<?php

declare(strict_types=1);

/**
 * Connexion MySQL — base par défaut : plateforme_unique (dump : sql/plateforme_unique.sql).
 * Surcharges : variables d'environnement DB_* ou fichier .env à la racine du projet.
 */
$root = dirname(__DIR__);
$envPath = $root . DIRECTORY_SEPARATOR . '.env';
if (is_readable($envPath)) {
    $lines = @file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($lines)) {
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || ($line[0] ?? '') === '#') {
                continue;
            }
            if (!str_contains($line, '=')) {
                continue;
            }
            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if ($name !== '' && getenv($name) === false) {
                putenv($name . '=' . $value);
                $_ENV[$name] = $value;
            }
        }
    }
}

$local = $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.local.php';
if (is_file($local)) {
    $override = require $local;
    if (is_array($override)) {
        return array_merge(
            [
                'host' => getenv('DB_HOST') ?: 'localhost',
                'user' => getenv('DB_USER') ?: 'root',
                'password' => getenv('DB_PASSWORD') ?: '',
                'database' => getenv('DB_DATABASE') ?: 'plateforme_unique',
            ],
            $override
        );
    }
}

return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'user' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'database' => getenv('DB_DATABASE') ?: 'plateforme_unique',
];
