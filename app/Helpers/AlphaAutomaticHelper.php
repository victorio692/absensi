<?php

namespace App\Helpers;

use App\Models\SiswaModel;
use App\Models\AbsensiModel;
use App\Models\IzinSakitModel;

class AlphaAutomaticHelper
{
    /**
     * Generate alpha otomatis untuk siswa yang tidak absen dan tidak memiliki izin/sakit
     * Panggil fungsi ini setiap hari jam 23:59 via cronjob atau scheduler
     * 
     * Command untuk testing: php spark alpha:generate [tanggal]
     */
    public static function generateAlpha($tanggal = null)
    {
        if ($tanggal === null) {
            $tanggal = date('Y-m-d');
        }

        $siswaModel = new SiswaModel();
        $absensiModel = new AbsensiModel();
        $izinSakitModel = new IzinSakitModel();

        try {
            // Ambil semua siswa (asumsi semua siswa di database adalah aktif)
            // Jika ada column 'status', filter by status='aktif'
            $db = \Config\Database::connect();
            $fields = $db->getFieldData('siswa');
            $fieldNames = array_column($fields, 'name');
            
            if (in_array('status', $fieldNames)) {
                $semuaSiswa = $siswaModel->where('status', 'aktif')->findAll();
            } else {
                $semuaSiswa = $siswaModel->findAll();
            }

            if (empty($semuaSiswa)) {
                log_message('info', 'Alpha Automatic: Tidak ada siswa untuk tanggal ' . $tanggal);
                return [
                    'success' => true,
                    'message' => 'Tidak ada siswa',
                    'generated' => 0,
                ];
            }

            $generated = 0;

            // Loop setiap siswa
            foreach ($semuaSiswa as $siswa) {
                $siswa_id = $siswa['id'];

                // Cek apakah siswa sudah memiliki absensi untuk hari ini
                $existingAbsensi = $absensiModel
                    ->where('siswa_id', $siswa_id)
                    ->where('DATE(tanggal)', $tanggal)
                    ->first();

                if ($existingAbsensi) {
                    // Sudah ada absensi, skip
                    continue;
                }

                // Cek apakah siswa memiliki izin/sakit yang disetujui untuk hari ini
                $existingIzin = $izinSakitModel
                    ->where('siswa_id', $siswa_id)
                    ->where('tanggal', $tanggal)
                    ->where('status', 'approved')
                    ->first();

                if ($existingIzin) {
                    // Sudah ada izin/sakit yang disetujui, skip
                    continue;
                }

                // Tidak ada absensi dan tidak ada izin/sakit yang disetujui
                // Insert alpha otomatis
                $absensiModel->insert([
                    'siswa_id' => $siswa_id,
                    'tanggal' => $tanggal,
                    'status' => 'alpha',
                    'source' => 'system',
                    'keterangan' => 'Alpha otomatis - tidak absen dan tidak ada izin/sakit',
                ]);

                $generated++;
            }

            log_message('info', 'Alpha Automatic: Generated ' . $generated . ' alpha records untuk tanggal ' . $tanggal);

            return [
                'success' => true,
                'message' => 'Alpha otomatis berhasil di-generate',
                'generated' => $generated,
                'tanggal' => $tanggal,
            ];
        } catch (\Exception $e) {
            log_message('error', 'Alpha Automatic Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'generated' => 0,
            ];
        }
    }

    /**
     * Cek apakah perlu generate alpha untuk tanggal tertentu
     * (untuk mencegah double generation)
     */
    public static function isAlphaAlreadyGenerated($tanggal)
    {
        $absensiModel = new AbsensiModel();

        // Cek apakah sudah ada absensi dengan source='system' dan status='alpha' untuk hari ini
        $existing = $absensiModel
            ->where('tanggal', $tanggal)
            ->where('source', 'system')
            ->where('status', 'alpha')
            ->countAllResults();

        return $existing > 0;
    }

    /**
     * Get summary alpha untuk tanggal tertentu
     */
    public static function getAlphaSummary($tanggal = null)
    {
        if ($tanggal === null) {
            $tanggal = date('Y-m-d');
        }

        $absensiModel = new AbsensiModel();

        $alpha = $absensiModel
            ->select('absensi.*, siswa.nama, siswa.kelas')
            ->join('siswa', 'siswa.id = absensi.siswa_id', 'left')
            ->where('absensi.tanggal', $tanggal)
            ->where('absensi.status', 'alpha')
            ->where('absensi.source', 'system')
            ->findAll();

        return [
            'tanggal' => $tanggal,
            'total' => count($alpha),
            'data' => $alpha,
        ];
    }
}
