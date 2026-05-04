<?php

declare(strict_types=1);

namespace App\Utils;

/**
 * Secure Token Generator
 */
final class TokenGenerator
{
    /**
     * Generate a unique, secure token
     * 
     * @param int $length Length in bytes (will be doubled when converted to hex)
     * @return string Hex-encoded random token
     */
    public static function generate(int $length = 16): string
    {
        return bin2hex(random_bytes($length));
    }
}
