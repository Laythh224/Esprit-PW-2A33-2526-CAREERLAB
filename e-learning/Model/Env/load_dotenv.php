<?php

declare(strict_types=1);

/**
 * Load KEY=VALUE lines from a .env file. Does not override vars already in the process environment.
 */
function careerlabb_load_dotenv_file(string $path): void
{
    if (!is_readable($path)) {
        return;
    }

    $raw = file_get_contents($path);
    if ($raw === false) {
        return;
    }

    // BOM UTF-8 : sinon la premiere cle (ex. TWILIO_*) est ignoree.
    if (str_starts_with($raw, "\xEF\xBB\xBF")) {
        $raw = substr($raw, 3);
    }

    $lines = preg_split("/\r\n|\n|\r/", $raw);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        if ($name === '' || !preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name)) {
            continue;
        }

        // Ne pas ecraser une variable deja definie avec une valeur non vide (Apache / OS).
        // Si elle est vide ou absente, le .env peut la renseigner.
        $existing = getenv($name);
        if ($existing !== false && $existing !== '') {
            continue;
        }

        $value = trim($value);
        if (
            strlen($value) >= 2
            && str_starts_with($value, '"')
            && str_ends_with($value, '"')
        ) {
            $value = stripcslashes(substr($value, 1, -1));
        } elseif (
            strlen($value) >= 2
            && str_starts_with($value, "'")
            && str_ends_with($value, "'")
        ) {
            $value = substr($value, 1, -1);
        }

        putenv($name . '=' . $value);
        $_ENV[$name] = $value;
    }
}
