<?php

/**
 * Connexion MySQL pour formations, sessions et clients (module e-learning).
 * Utilise la même base que {@see platform_database.php} pour que les inscriptions
 * (table `client`) apparaissent avec les comptes plateforme dans un seul schéma MySQL.
 *
 * Pour rétablir une base dédiée (ex. `e_learning`), définissez une copie locale avec un autre `dbname`.
 */
$platform = require __DIR__ . '/platform_database.php';

return [
    'host' => $platform['host'],
    'port' => $platform['port'],
    'dbname' => $platform['dbname'],
    'username' => $platform['username'],
    'password' => $platform['password'],
    'charset' => $platform['charset'] ?? 'utf8mb4',
];
