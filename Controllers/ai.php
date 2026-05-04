<?php

$envPath = __DIR__ . '/.env';
if (is_file($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = array_map('trim', explode('=', $line, 2));
        $value = trim($value, "\"'");
        if ($key !== '' && getenv($key) === false) {
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
        }
    }
}

return [
    'api_key' => getenv('OPENAI_API_KEY') ?: '',
    'model' => getenv('OPENAI_MODEL') ?: 'gpt-4.1-mini',
    'endpoint' => getenv('OPENAI_ENDPOINT') ?: 'https://api.openai.com/v1/responses',
];
