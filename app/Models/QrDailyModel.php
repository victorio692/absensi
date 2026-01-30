<?php

namespace App\Models;

use CodeIgniter\Model;

class QrDailyModel extends Model
{
    protected $table = 'qr_daily';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['location_id', 'tanggal', 'token', 'created_at'];
    protected $useTimestamps = false;

    /**
     * Secret key untuk generate token (simpan di .env)
     */
    private function getSecretKey()
    {
        return env('QR_SECRET_KEY', 'default-secret-key-change-this');
    }

    /**
     * Generate token untuk QR code
     * Format: hash(location_id + tanggal + secret_key)
     */
    public function generateToken($locationId, $tanggal)
    {
        $data = $locationId . $tanggal . $this->getSecretKey();
        return hash('sha256', $data);
    }

    /**
     * Generate QR untuk satu lokasi di hari tertentu
     */
    public function generateQrForLocation($locationId, $tanggal = null)
    {
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        // Cek apakah sudah ada QR untuk hari ini
        $existing = $this->where('location_id', $locationId)
                        ->where('tanggal', $tanggal)
                        ->first();

        if ($existing) {
            return $existing;
        }

        // Generate token baru
        $token = $this->generateToken($locationId, $tanggal);

        // Simpan ke database
        $data = [
            'location_id' => $locationId,
            'tanggal'     => $tanggal,
            'token'       => $token,
        ];

        $this->insert($data);

        return $this->find($this->getInsertID());
    }

    /**
     * Generate QR untuk semua lokasi aktif hari ini
     */
    public function generateDailyQr($tanggal = null)
    {
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        $qrLocationModel = new QrLocationModel();
        $lokasi = $qrLocationModel->getAktif();

        $generated = [];
        foreach ($lokasi as $loc) {
            $qr = $this->generateQrForLocation($loc['id'], $tanggal);
            $generated[] = $qr;
        }

        return $generated;
    }

    /**
     * Get QR content untuk dicetak/ditampilkan
     * Format: location_id|tanggal|token
     */
    public function getQrContent($locationId, $tanggal = null)
    {
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        $qr = $this->where('location_id', $locationId)
                   ->where('tanggal', $tanggal)
                   ->first();

        if (!$qr) {
            return null;
        }

        return $locationId . '|' . $tanggal . '|' . $qr['token'];
    }

    /**
     * Validasi QR code yang di-scan
     * Return: [valid => true/false, location_id => ..., message => ...]
     */
    public function validateQr($qrContent)
    {
        // Parse QR content: location_id|tanggal|token
        $parts = explode('|', $qrContent);

        if (count($parts) !== 3) {
            return [
                'valid'       => false,
                'message'     => 'Format QR tidak valid',
                'location_id' => null,
            ];
        }

        list($locationId, $tanggal, $token) = $parts;

        // Validasi format
        if (!is_numeric($locationId)) {
            return [
                'valid'       => false,
                'message'     => 'Lokasi QR tidak valid',
                'location_id' => null,
            ];
        }

        $hariIni = date('Y-m-d');

        // Cek apakah tanggal = hari ini
        if ($tanggal !== $hariIni) {
            return [
                'valid'       => false,
                'message'     => 'QR hanya valid untuk hari ini',
                'location_id' => $locationId,
            ];
        }

        // Cek QR di database
        $qr = $this->where('location_id', $locationId)
                   ->where('tanggal', $tanggal)
                   ->first();

        if (!$qr) {
            return [
                'valid'       => false,
                'message'     => 'QR tidak ditemukan',
                'location_id' => $locationId,
            ];
        }

        // Cek token
        if ($qr['token'] !== $token) {
            return [
                'valid'       => false,
                'message'     => 'Token QR tidak cocok',
                'location_id' => $locationId,
            ];
        }

        // Cek apakah lokasi masih aktif
        $qrLocationModel = new QrLocationModel();
        $lokasi = $qrLocationModel->isActive($locationId);

        if (!$lokasi) {
            return [
                'valid'       => false,
                'message'     => 'Lokasi QR tidak aktif',
                'location_id' => $locationId,
            ];
        }

        // Cek jam masuk
        $pengaturanModel = new PengaturanModel();
        $pengaturan = $pengaturanModel->getPengaturan();

        $jamSekarang = strtotime(date('H:i:s'));
        $jasMasuk = strtotime($pengaturan['jam_masuk']);

        if ($jamSekarang > $jasMasuk) {
            // Terlambat, tapi masih bisa absen
            $status = 'Terlambat';
        } else {
            $status = 'Hadir';
        }

        // QR VALID!
        return [
            'valid'       => true,
            'message'     => 'QR valid',
            'location_id' => (int) $locationId,
            'tanggal'     => $tanggal,
            'status'      => $status,
        ];
    }

    /**
     * Ambil QR untuk lokasi dan tanggal tertentu
     */
    public function getByLocationAndDate($locationId, $tanggal = null)
    {
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        return $this->where('location_id', $locationId)
                    ->where('tanggal', $tanggal)
                    ->first();
    }

    /**
     * Ambil semua QR hari ini dengan nama lokasi
     */
    public function getTodayWithLocation()
    {
        return $this->select('qr_daily.*, qr_location.nama_lokasi')
                    ->join('qr_location', 'qr_location.id = qr_daily.location_id')
                    ->where('qr_daily.tanggal', date('Y-m-d'))
                    ->orderBy('qr_location.nama_lokasi', 'ASC')
                    ->findAll();
    }
}
