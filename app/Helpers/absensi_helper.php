<?php

/**
 * Helper untuk generate QR Code
 * Menggunakan library simple PHP tanpa dependencies
 */

if (!function_exists('generateQRCode')) {
    /**
     * Generate QR Code dalam format HTML img tag
     * 
     * @param string $data - Data yang akan di-encode ke QR
     * @param int $size - Ukuran QR (default 200)
     * @return string - HTML img tag dengan base64 encoded QR
     */
    function generateQRCode($data, $size = 200)
    {
        // URL QR code generator API gratis
        $encodedData = urlencode($data);
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=" . $size . "x" . $size . "&data=" . $encodedData;
        
        return $qrUrl;
    }
}

if (!function_exists('tanggalIndo')) {
    /**
     * Format tanggal ke bahasa Indonesia
     */
    function tanggalIndo($date)
    {
        if (empty($date)) {
            return '-';
        }

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $timestamp = strtotime($date);
        $tgl = date('j', $timestamp);
        $bln = $bulan[date('n', $timestamp)];
        $thn = date('Y', $timestamp);

        return "$tgl $bln $thn";
    }
}

if (!function_exists('hariIndo')) {
    /**
     * Dapatkan nama hari dalam bahasa Indonesia
     */
    function hariIndo($date)
    {
        $hari = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu'
        ];

        $timestamp = strtotime($date);
        $dayName = date('l', $timestamp);

        return $hari[$dayName] ?? $dayName;
    }
}

if (!function_exists('jamFormat')) {
    /**
     * Format jam dengan kondisional
     */
    function jamFormat($jam)
    {
        if (empty($jam)) {
            return '-';
        }
        return date('H:i', strtotime($jam));
    }
}

if (!function_exists('durasi')) {
    /**
     * Hitung durasi antara jam masuk dan jam pulang
     */
    function durasi($jamMasuk, $jamPulang)
    {
        if (empty($jamMasuk) || empty($jamPulang)) {
            return '-';
        }

        $masuk = strtotime($jamMasuk);
        $pulang = strtotime($jamPulang);
        $selisih = $pulang - $masuk;
        $jam = floor($selisih / 3600);
        $menit = ($selisih % 3600) / 60;

        return $jam . ' jam ' . round($menit) . ' menit';
    }
}

if (!function_exists('badgeStatus')) {
    /**
     * Generate badge HTML berdasarkan status
     */
    function badgeStatus($status)
    {
        $colors = [
            'Hadir'     => 'success',
            'Terlambat' => 'warning',
            'Izin'      => 'info',
            'Sakit'     => 'danger',
        ];

        $color = $colors[$status] ?? 'secondary';

        return "<span class='badge bg-{$color}'>{$status}</span>";
    }
}
