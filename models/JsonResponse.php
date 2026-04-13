<?php

class JsonResponse
{
    public static function send(bool $ok, array $data = [], int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode(array_merge(['ok' => $ok], $data), JSON_UNESCAPED_UNICODE);
        exit;
    }
}
