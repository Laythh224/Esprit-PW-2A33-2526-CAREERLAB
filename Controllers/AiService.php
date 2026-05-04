<?php

class AiService
{
    private string $apiKey;
    private string $model;
    private string $endpoint;

    public function __construct(array $config = [])
    {
        $this->apiKey = trim((string) ($config['api_key'] ?? ''));
        $this->model = trim((string) ($config['model'] ?? 'gpt-4.1-mini'));
        $this->endpoint = trim((string) ($config['endpoint'] ?? 'https://api.openai.com/v1/responses'));
    }

    public function answer(string $message, string $email = '', string $name = ''): array
    {
        $requestType = $this->classify($message);
        $fallback = $this->fallbackAnswer($requestType);

        if ($this->apiKey === '') {
            return [
                'request_type' => $requestType,
                'response' => $fallback,
                'status' => 'fallback',
            ];
        }

        try {
            $response = $this->callOpenAi($message, $email, $name, $requestType);
            return [
                'request_type' => $requestType,
                'response' => $response !== '' ? $response : $fallback,
                'status' => $response !== '' ? 'generated' : 'fallback',
            ];
        } catch (Throwable $exception) {
            error_log('AI assistant unavailable: ' . $exception->getMessage());
            return [
                'request_type' => $requestType,
                'response' => $fallback,
                'status' => 'fallback',
            ];
        }
    }

    private function classify(string $message): string
    {
        $text = mb_strtolower($message, 'UTF-8');

        if ($this->containsAny($text, ['mot de passe', 'password', 'reinitial', 'réinitial', 'oublie', 'oublié'])) {
            return 'mot_de_passe_oublie';
        }

        if ($this->containsAny($text, ['verification', 'vérification', 'verifier', 'vérifier', 'code', 'email', 'mail'])) {
            return 'verification_email';
        }

        if ($this->containsAny($text, ['connexion', 'connecter', 'login', 'identifiant', 'compte bloque', 'compte bloqué'])) {
            return 'probleme_connexion';
        }

        if ($this->containsAny($text, ['question', 'information', 'aide', 'support', 'contact'])) {
            return 'demande_generale';
        }

        return 'autre_probleme';
    }

    private function containsAny(string $text, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($text, $needle)) {
                return true;
            }
        }

        return false;
    }

    private function fallbackAnswer(string $requestType): string
    {
        return match ($requestType) {
            'mot_de_passe_oublie' => "Votre demande semble concerner un mot de passe oublie. Utilisez la fonctionnalite existante \"Mot de passe oublie ?\" sur la page de connexion afin de recevoir un lien de reinitialisation par email.",
            'verification_email' => "Votre demande semble concerner la verification email. Verifiez votre boite mail, y compris les spams, puis saisissez le code recu dans la page de verification.",
            'probleme_connexion' => "Votre demande semble concerner la connexion. Verifiez d'abord votre email et votre mot de passe. Si le probleme continue, utilisez la reinitialisation du mot de passe depuis la page de connexion.",
            'demande_generale' => "Merci pour votre message. Votre demande a bien ete recue et notre assistant vous oriente vers l'equipe CareerLab si une action humaine est necessaire.",
            default => "Merci pour votre message. Votre demande a bien ete enregistree. Veuillez verifier les informations de votre compte et reessayer; si le probleme persiste, l'administrateur pourra consulter votre demande.",
        };
    }

    private function callOpenAi(string $message, string $email, string $name, string $requestType): string
    {
        if (!function_exists('curl_init')) {
            throw new RuntimeException('Extension cURL indisponible.');
        }

        $payload = [
            'model' => $this->model,
            'instructions' => "Tu es l'assistant support de CareerLab. Reponds en francais, de facon courte, claire et utile. Classe mentalement la demande parmi: probleme de connexion, mot de passe oublie, verification email, demande generale, autre probleme. Si la demande concerne le mot de passe oublie, conseille d'utiliser la fonctionnalite existante de reinitialisation. Si elle concerne la verification email, explique de verifier la boite mail et de saisir le code recu. Ne demande jamais le mot de passe.",
            'input' => "Type detecte: {$requestType}\nNom: {$name}\nEmail: {$email}\nMessage utilisateur:\n{$message}",
            'max_output_tokens' => 220,
        ];

        $ch = curl_init($this->endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey,
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            CURLOPT_TIMEOUT => 15,
        ]);

        $raw = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($raw === false || $raw === '' || $status < 200 || $status >= 300) {
            throw new RuntimeException($error !== '' ? $error : 'Reponse API invalide.');
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('JSON API invalide.');
        }

        if (isset($decoded['output_text']) && is_string($decoded['output_text'])) {
            return trim($decoded['output_text']);
        }

        return $this->extractOutputText($decoded['output'] ?? []);
    }

    private function extractOutputText(array $output): string
    {
        $texts = [];

        foreach ($output as $item) {
            if (!is_array($item)) {
                continue;
            }

            foreach (($item['content'] ?? []) as $content) {
                if (is_array($content) && isset($content['text']) && is_string($content['text'])) {
                    $texts[] = $content['text'];
                }
            }
        }

        return trim(implode("\n", $texts));
    }
}
