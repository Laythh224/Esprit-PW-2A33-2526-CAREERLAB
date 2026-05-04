<?php

declare(strict_types=1);

namespace App\Utils;

/**
 * QR Code Generator using QR Server API.
 * The encoded payload should be raw result data, not a navigation URL.
 */
final class QRCodeGenerator
{
    private const QR_SERVER_URL = 'https://api.qrserver.com/v1/create-qr-code/';
    
    /**
     * Generate QR code URL using QR Server API
     * 
     * @param string $data The data to encode
     * @param int $size Size in pixels (default: 300)
     * @return string The QR code image URL
     */
    public static function generateQRCodeUrl(string $data, int $size = 300): string
    {
        $encodedData = urlencode($data);
        
        return self::QR_SERVER_URL . '?size=' . $size . 'x' . $size 
            . '&data=' . $encodedData;
    }
    
    /**
     * Generate QR code as HTML img tag
     * 
     * @param string $data The data to encode
     * @param int $size Size in pixels
     * @param string $altText Alternative text for the image
     * @return string HTML img tag
     */
    public static function generateQRCodeHtml(
        string $data, 
        int $size = 300, 
        string $altText = 'QR Code'
    ): string {
        $url = self::generateQRCodeUrl($data, $size);
        $escapedAlt = htmlspecialchars($altText, ENT_QUOTES, 'UTF-8');
        
        return '<img src="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" 
                    alt="' . $escapedAlt . '" 
                    width="' . $size . '" 
                    height="' . $size . '" 
                    class="qr-code-image" />';
    }
}
