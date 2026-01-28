<?php

namespace App\Helpers;

/**
 * QR Code Helper - untuk generate dan validasi QR dinamis
 */
class QrHelper
{
    /**
     * Generate token untuk QR Code
     * Format: hash(location_id|tanggal|secret_key)
     */
    public static function generateToken($locationId, $date = null)
    {
        $date = $date ?? date('Y-m-d');
        $encryptionKey = config('App')->encryptionKey;
        
        return hash('sha256', $locationId . '|' . $date . '|' . $encryptionKey);
    }

    /**
     * Generate QR Content
     * Format: location_id|tanggal|token
     */
    public static function generateQrContent($locationId, $date = null)
    {
        $date = $date ?? date('Y-m-d');
        $token = self::generateToken($locationId, $date);
        
        return $locationId . '|' . $date . '|' . $token;
    }

    /**
     * Parse QR Content ke array
     */
    public static function parseQrContent($content)
    {
        $parts = explode('|', $content);
        
        if (count($parts) !== 3) {
            return null;
        }

        return [
            'location_id' => (int)$parts[0],
            'tanggal'     => $parts[1],
            'token'       => $parts[2],
        ];
    }

    /**
     * Validasi QR Token
     */
    public static function validateToken($locationId, $date, $token)
    {
        $expectedToken = self::generateToken($locationId, $date);
        return hash_equals($expectedToken, $token);
    }

    /**
     * Generate QR Code image URL
     * Menggunakan external API (qrserver.com)
     */
    public static function generateQrImageUrl($content, $size = 300)
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . urlencode($content);
    }

    /**
     * Download dan cache QR Code image
     */
    public static function cacheQrImage($content)
    {
        $filename = md5($content) . '.png';
        $cachePath = WRITEPATH . 'uploads/qr_codes/' . $filename;
        
        if (!is_dir(WRITEPATH . 'uploads/qr_codes/')) {
            @mkdir(WRITEPATH . 'uploads/qr_codes/', 0755, true);
        }

        if (!file_exists($cachePath)) {
            $url = self::generateQrImageUrl($content, 300);
            $imageData = @file_get_contents($url);
            
            if ($imageData) {
                file_put_contents($cachePath, $imageData);
            }
        }

        return $cachePath;
    }

    /**
     * Get QR image sebagai base64
     */
    public static function getQrImageBase64($content)
    {
        $cachePath = self::cacheQrImage($content);
        
        if (file_exists($cachePath)) {
            $imageData = file_get_contents($cachePath);
            return 'data:image/png;base64,' . base64_encode($imageData);
        }

        return null;
    }
}
