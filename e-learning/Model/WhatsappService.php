<?php

declare(strict_types=1);

namespace App\Model;

class WhatsappService
{
    private static ?array $configCache = null;

    /**
     * Suffixe optionnel pour le message flash (si flash_whatsapp_debug dans la config).
     */
    public function notifyInscription(string $phoneLocal8, string $nomFormation): string
    {
        $config = $this->loadConfig();

        $this->debugLog($config, '----------');
        $this->debugLog($config, 'Inscription : tel=' . $phoneLocal8 . ', formation=' . $nomFormation);

        if (empty($config['enabled'])) {
            $this->debugLog($config, 'SKIP : enabled=false dans Model/config/whatsapp.php -> passer a true et renseigner le provider.');
            return $this->flashSuffix($config, 'WhatsApp desactive dans config');
        }

        $baseMessage = sprintf(
            'CareerLab : votre inscription a la formation « %s » est confirmee. Merci !',
            $nomFormation
        );

        $e164Client = $this->toE164($phoneLocal8, (string) ($config['country_calling_code'] ?? '216'));
        if ($e164Client === null) {
            $this->debugLog($config, 'ERREUR : tel doit avoir exactement 8 chiffres.');
            return $this->flashSuffix($config, 'WhatsApp : numero invalide');
        }

        $this->debugLog($config, 'Numero international utilise : ' . $e164Client);

        $provider = (string) ($config['provider'] ?? 'callmebot');

        $callmebot = $config['callmebot'] ?? [];
        $twilioCfg = $config['twilio'] ?? [];
        $sendToAdmin = !empty($callmebot['send_to_admin_phone']);
        $adminPhone = trim((string) ($callmebot['admin_phone'] ?? ''));

        $destination = $e164Client;
        $message = $baseMessage;

        if ($provider === 'callmebot' && $sendToAdmin) {
            if ($adminPhone === '' || $adminPhone[0] !== '+') {
                $this->debugLog($config, 'ERREUR : admin_phone manquant ou sans + (ex. +21612345678).');
                return $this->flashSuffix($config, 'WhatsApp : admin_phone invalide');
            }
            $destination = $adminPhone;
            $message = sprintf(
                '[CareerLab] Nouvelle inscription — formation « %s ». Tel client : %s',
                $nomFormation,
                $e164Client
            );
        } elseif ($provider === 'twilio' && !empty($twilioCfg['send_to_admin_phone'])) {
            $adminTwilio = trim((string) ($twilioCfg['admin_phone'] ?? ''));
            if ($adminTwilio === '' || $adminTwilio[0] !== '+') {
                $this->debugLog($config, 'ERREUR Twilio : admin_phone manquant ou sans + (ex. +21612345678).');
                return $this->flashSuffix($config, 'WhatsApp : admin_phone invalide');
            }
            $destination = $adminTwilio;
            $message = sprintf(
                '[CareerLab] Nouvelle inscription — formation « %s ». Tel client : %s',
                $nomFormation,
                $e164Client
            );
        }

        if ($provider === 'twilio' && !$this->twilioCredentialsReady($config)) {
            $this->debugLog(
                $config,
                'SKIP Twilio : pas de AC + token (.env TWILIO_* ou variables serveur). Pas d appel API — inscription OK.'
            );

            return '';
        }

        try {
            if ($provider === 'callmebot') {
                $this->sendViaCallMeBot($config, $destination, $message);
            } elseif ($provider === 'twilio') {
                $this->sendViaTwilioWhatsapp($config, $destination, $message, $nomFormation, $e164Client);
            } elseif ($provider === 'webhook') {
                $this->sendViaWebhook($config, $destination, $message);
            } else {
                $this->debugLog($config, 'ERREUR : provider inconnu ' . $provider);
                return $this->flashSuffix($config, 'WhatsApp : provider inconnu');
            }

            $this->debugLog($config, 'Termine sans exception.');
            return $this->flashSuffix($config, 'WhatsApp : envoye');
        } catch (\Throwable $e) {
            $this->debugLog($config, 'EXCEPTION : ' . $e->getMessage());
            error_log('WhatsApp: ' . $e->getMessage());
            return $this->flashSuffix($config, 'WhatsApp erreur — voir storage/logs/whatsapp.log');
        }
    }

    private function flashSuffix(array $config, string $shortMessage): string
    {
        if (empty($config['flash_whatsapp_debug'])) {
            return '';
        }

        return ' [' . $shortMessage . ']';
    }

    /**
     * @param mixed $config
     */
    private function debugLog(array $config, string $line): void
    {
        $path = isset($config['debug_log_file']) ? trim((string) $config['debug_log_file']) : '';
        if ($path === '') {
            return;
        }

        $dir = dirname($path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        @file_put_contents(
            $path,
            date('Y-m-d H:i:s') . ' ' . $line . "\n",
            FILE_APPEND | LOCK_EX
        );
    }

    /**
     * True si Twilio peut etre appele (Account SID AC... + auth ou API Key SK...).
     */
    private function twilioCredentialsReady(array $config): bool
    {
        $t = $config['twilio'] ?? [];
        $sid = trim((string) ($t['account_sid'] ?? ''));
        $token = trim((string) ($t['auth_token'] ?? ''));
        $apiKs = trim((string) ($t['api_key_sid'] ?? ''));
        $apiSecret = trim((string) ($t['api_key_secret'] ?? ''));

        if ($sid === '' || strncmp($sid, 'AC', 2) !== 0 || stripos($sid, 'xxxx') !== false) {
            return false;
        }

        if ($apiKs !== '' && $apiSecret !== '') {
            return true;
        }

        if ($token === '') {
            return false;
        }

        if (stripos($token, 'REMPLACEZ') !== false || stripos($token, 'collez') !== false) {
            return false;
        }

        return true;
    }

    private function loadConfig(): array
    {
        if (self::$configCache !== null) {
            return self::$configCache;
        }

        $path = __DIR__ . '/config/whatsapp.php';
        if (!is_file($path)) {
            return self::$configCache = ['enabled' => false];
        }

        $loaded = require $path;

        return self::$configCache = is_array($loaded) ? $loaded : ['enabled' => false];
    }

    private function toE164(string $digits8, string $countryCallingCode): ?string
    {
        $digits = preg_replace('/\D/', '', $digits8) ?? '';
        if (strlen($digits) !== 8) {
            return null;
        }

        $cc = preg_replace('/\D/', '', $countryCallingCode) ?? '';
        if ($cc === '') {
            return null;
        }

        return '+' . $cc . $digits;
    }

    private function formatPhoneForCallMeBot(array $config, string $toE164): string
    {
        $noPlus = !empty(($config['callmebot'] ?? [])['phone_without_plus']);
        if (!$noPlus) {
            return $toE164;
        }

        return ltrim($toE164, '+');
    }

    private function sendViaCallMeBot(array $config, string $toE164, string $body): void
    {
        $key = trim((string) (($config['callmebot'] ?? [])['apikey'] ?? ''));
        if ($key === '') {
            $this->debugLog($config, 'ERREUR : callmebot.apikey vide.');
            throw new \RuntimeException('apikey CallMeBot manquant');
        }

        $phoneParam = $this->formatPhoneForCallMeBot($config, $toE164);
        $this->debugLog($config, 'CallMeBot phone param : ' . $phoneParam);

        $url = sprintf(
            'https://api.callmebot.com/whatsapp.php?source=php&phone=%s&text=%s&apikey=%s',
            rawurlencode($phoneParam),
            rawurlencode($body),
            rawurlencode($key)
        );

        $result = $this->httpGet($url, $config);
        $response = $result['body'];
        $code = $result['code'];

        $snippet = function_exists('mb_substr')
            ? mb_substr(trim($response), 0, 400, 'UTF-8')
            : substr(trim($response), 0, 400);
        $this->debugLog($config, 'HTTP ' . $code . ' reponse : ' . $snippet);

        if ($code === 0) {
            throw new \RuntimeException('HTTP 0 — pas de reponse (reseau, SSL, ou curl). Essayez curl_ssl_verify=false en local.');
        }

        if ($code < 200 || $code >= 300) {
            throw new \RuntimeException('HTTP ' . $code . ' — ' . $response);
        }

        $lower = strtolower($response);
        if (
            strpos($lower, 'error') !== false
            || strpos($lower, 'not allowed') !== false
            || strpos($lower, 'invalid apikey') !== false
            || (strpos($lower, 'invalid') !== false && strpos($lower, 'phone') !== false)
        ) {
            throw new \RuntimeException('CallMeBot : ' . trim($response));
        }
    }

    /**
     * @return array{code: int, body: string}
     */
    private function httpGet(string $url, array $config): array
    {
        $verifySsl = array_key_exists('curl_ssl_verify', $config) ? (bool) $config['curl_ssl_verify'] : true;

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            if ($ch === false) {
                throw new \RuntimeException('curl_init failed');
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verifySsl);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $verifySsl ? 2 : 0);
            $response = curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_error($ch);
            curl_close($ch);
            if ($response === false) {
                throw new \RuntimeException('cURL : ' . $err);
            }

            return ['code' => $code, 'body' => (string) $response];
        }

        $ctx = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 30,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => $verifySsl,
                'verify_peer_name' => $verifySsl,
            ],
        ]);
        $response = @file_get_contents($url, false, $ctx);
        if ($response === false) {
            throw new \RuntimeException('file_get_contents a echoue (reseau ou SSL).');
        }
        $statusLine = $http_response_header[0] ?? '';
        $code = 0;
        if (preg_match('#\s(\d{3})\s#', $statusLine, $m)) {
            $code = (int) $m[1];
        }

        return ['code' => $code, 'body' => (string) $response];
    }

    /**
     * @param string $formationName nom de formation (pour ContentVariables)
     * @param string $clientE164    tel client +216...
     */
    private function sendViaTwilioWhatsapp(
        array $config,
        string $toE164,
        string $body,
        string $formationName = '',
        string $clientE164 = ''
    ): void {
        $twilio = $config['twilio'] ?? [];
        $sid = trim((string) ($twilio['account_sid'] ?? ''));
        $token = trim((string) ($twilio['auth_token'] ?? ''));
        $from = trim((string) ($twilio['from'] ?? ''));
        $apiKeySid = trim((string) ($twilio['api_key_sid'] ?? ''));
        $apiKeySecret = trim((string) ($twilio['api_key_secret'] ?? ''));

        if ($from === '') {
            $this->debugLog($config, 'ERREUR Twilio : from vide.');
            throw new \RuntimeException('Twilio incomplet');
        }

        if ($sid === '' || strncmp($sid, 'AC', 2) !== 0) {
            $this->debugLog($config, 'ERREUR Twilio : account_sid doit etre le Account SID (commence par AC), pas une API Key ou un autre identifiant.');
            throw new \RuntimeException('Twilio : account_sid doit commencer par AC');
        }

        if (
            (stripos($token, 'REMPLACEZ') !== false || stripos($token, 'collez') !== false)
            || (stripos($sid, 'xxxx') !== false)
        ) {
            $this->debugLog($config, 'ERREUR Twilio : renseignez TWILIO_AUTH_TOKEN dans .env (ou API Key / variables serveur).');
            throw new \RuntimeException('Twilio : configurez TWILIO_AUTH_TOKEN ou API Key dans .env');
        }

        $useApiKey = $apiKeySid !== '' && $apiKeySecret !== '';
        if (!$useApiKey && $token === '') {
            $this->debugLog($config, 'ERREUR Twilio : auth_token ou couple api_key_sid + api_key_secret requis.');
            throw new \RuntimeException('Twilio incomplet');
        }

        $authUser = $useApiKey ? $apiKeySid : $sid;
        $authPass = $useApiKey ? $apiKeySecret : $token;

        $toWa = 'whatsapp:' . $toE164;

        $url = sprintf('https://api.twilio.com/2010-04-01/Accounts/%s/Messages.json', rawurlencode($sid));

        $contentSid = trim((string) ($twilio['content_sid'] ?? ''));
        if ($contentSid !== '') {
            $vars = $this->buildTwilioContentVariables($twilio, $body, $formationName, $clientE164);
            $encodedVars = json_encode($vars, JSON_UNESCAPED_UNICODE);
            if ($encodedVars === false) {
                throw new \RuntimeException('Twilio ContentVariables JSON encode failed');
            }
            $this->debugLog($config, 'Twilio modele ContentSid=' . $contentSid . ' variables=' . $encodedVars);
            $payload = http_build_query([
                'From' => $from,
                'To' => $toWa,
                'ContentSid' => $contentSid,
                'ContentVariables' => $encodedVars,
            ], '', '&', PHP_QUERY_RFC3986);
        } else {
            $payload = http_build_query([
                'From' => $from,
                'To' => $toWa,
                'Body' => $body,
            ], '', '&', PHP_QUERY_RFC3986);
        }

        $verifySsl = array_key_exists('curl_ssl_verify', $config) ? (bool) $config['curl_ssl_verify'] : true;
        $responseBody = $this->httpPost($url, $payload, [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic ' . base64_encode($authUser . ':' . $authPass),
        ], $verifySsl);

        $snippet = function_exists('mb_substr')
            ? mb_substr(trim($responseBody), 0, 500, 'UTF-8')
            : substr(trim($responseBody), 0, 500);
        $this->debugLog($config, 'Twilio reponse JSON : ' . $snippet);
    }

    /**
     * Mappe content_variable_map (cles = indices du modele "1","2",...) vers formation | tel | message.
     *
     * @return array<string, string>
     */
    private function buildTwilioContentVariables(array $twilio, string $body, string $formationName, string $clientE164): array
    {
        $map = $twilio['content_variable_map'] ?? null;
        if ($map === null || !is_array($map) || $map === []) {
            return ['1' => $formationName !== '' ? $formationName : $body];
        }

        $out = [];
        foreach ($map as $slot => $source) {
            $slotKey = (string) $slot;
            $src = (string) $source;
            if ($src === 'formation') {
                $out[$slotKey] = $formationName;
            } elseif ($src === 'tel') {
                $out[$slotKey] = $clientE164;
            } elseif ($src === 'message') {
                $out[$slotKey] = $body;
            } else {
                $out[$slotKey] = $src;
            }
        }

        return $out;
    }

    private function sendViaWebhook(array $config, string $toE164, string $body): void
    {
        $hook = $config['webhook'] ?? [];
        $url = trim((string) ($hook['url'] ?? ''));
        if ($url === '') {
            throw new \RuntimeException('webhook URL vide');
        }

        $method = strtoupper((string) ($hook['method'] ?? 'POST'));
        $json = (bool) ($hook['json'] ?? true);
        $phoneKey = (string) ($hook['phone_key'] ?? 'phone');
        $messageKey = (string) ($hook['message_key'] ?? 'message');
        $headers = $hook['headers'] ?? [];
        if (!is_array($headers)) {
            $headers = [];
        }

        $data = [$phoneKey => $toE164, $messageKey => $body];
        $verifySsl = array_key_exists('curl_ssl_verify', $config) ? (bool) $config['curl_ssl_verify'] : true;

        if ($json) {
            $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
            if ($payload === false) {
                throw new \RuntimeException('JSON encode failed');
            }
            $headers[] = 'Content-Type: application/json; charset=UTF-8';
            $this->httpRequest($url, $method, $payload, $headers, $verifySsl);

            return;
        }

        $payload = http_build_query($data);
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $this->httpRequest($url, $method, $payload, $headers, $verifySsl);
    }

    /**
     * @param list<string> $headers
     */
    private function httpPost(string $url, string $body, array $headers, bool $verifySsl): string
    {
        return $this->httpRequest($url, 'POST', $body, $headers, $verifySsl);
    }

    /**
     * @param list<string> $headers
     * @return string corps HTTP en cas de succes
     */
    private function httpRequest(string $url, string $method, string $body, array $headers, bool $verifySsl): string
    {
        if (!function_exists('curl_init')) {
            throw new \RuntimeException('Extension curl PHP absente : activez php_curl dans php.ini.');
        }

        $ch = curl_init($url);
        if ($ch === false) {
            throw new \RuntimeException('curl_init failed');
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verifySsl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $verifySsl ? 2 : 0);
        $response = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($response === false || $code < 200 || $code >= 300) {
            throw new \RuntimeException('HTTP ' . $code . ' — ' . (string) $response);
        }

        return (string) $response;
    }
}
