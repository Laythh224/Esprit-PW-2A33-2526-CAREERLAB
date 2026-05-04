<?php

class PdfController
{
    public function generate(array $payload = []): void
    {
        if (!headers_sent()) {
            http_response_code(501);
            header('Content-Type: application/json; charset=utf-8');
        }

        echo json_encode([
            'ok' => false,
            'error' => 'Fonction PDF non active sur ce projet.',
            'details' => 'Cette route est un alias de compatibilite avec le modele.',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}

