<?php

declare(strict_types=1);

/**
 * Test rapide de la config WhatsApp (sans navigateur).
 * Usage : cd e-learning && php scripts/test-whatsapp.php
 */
$base = dirname(__DIR__);

spl_autoload_register(function (string $class) use ($base): void {
    $prefix = 'App\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $relativeClass = substr($class, strlen($prefix));
    $file = $base . '/' . str_replace('\\', '/', $relativeClass) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

use App\Model\WhatsappService;

$configPath = $base . '/Model/config/whatsapp.php';
$localPath = $base . '/Model/config/whatsapp.local.php';

echo "Config : " . $configPath . PHP_EOL;
$envFile = $base . DIRECTORY_SEPARATOR . '.env';
echo ".env    : " . (is_file($envFile) ? 'oui (' . $envFile . ')' : 'non (copier .env.example -> .env)') . PHP_EOL;
echo "Local  : " . (is_file($localPath) ? 'oui (' . $localPath . ')' : 'non') . PHP_EOL;

$raw = require $configPath;
$enabled = !empty($raw['enabled']);
$provider = (string) ($raw['provider'] ?? '');
echo "enabled  : " . ($enabled ? 'true' : 'false') . PHP_EOL;
echo "provider : " . $provider . PHP_EOL;

$tw = $raw['twilio'] ?? [];
$sidOk = trim((string) ($tw['account_sid'] ?? '')) !== '';
$tokOk = trim((string) ($tw['auth_token'] ?? '')) !== '';
echo "twilio   : account_sid " . ($sidOk ? 'renseigne' : 'VIDE') . ", auth_token " . ($tokOk ? 'renseigne' : 'VIDE') . PHP_EOL;

echo '---' . PHP_EOL;
echo "Appel notifyInscription(12345678, 'Test', 'Jean Dupont')..." . PHP_EOL;

$svc = new WhatsappService();
$suffix = $svc->notifyInscription('12345678', 'Test', 'Jean Dupont');
echo "Suffixe flash (comme apres inscription) : " . ($suffix === '' ? '(vide)' : $suffix) . PHP_EOL;
