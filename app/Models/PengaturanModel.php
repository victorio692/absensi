<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaturanModel extends Model
{
    protected $table = 'pengaturan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['jam_masuk', 'batas_terlambat', 'jam_pulang', 'batas_alpha'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Validasi
     */
    protected $validationRules = [
        'jam_masuk'       => 'required|valid_date[H:i]',
        'batas_terlambat' => 'required|valid_date[H:i]',
        'jam_pulang'      => 'required|valid_date[H:i]',
        'batas_alpha'     => 'required|valid_date[H:i]',
    ];

    protected $validationMessages = [
        'jam_masuk' => [
            'required' => 'Jam masuk harus diisi',
            'valid_date' => 'Format jam tidak valid',
        ],
        'batas_terlambat' => [
            'required' => 'Batas terlambat harus diisi',
            'valid_date' => 'Format jam tidak valid',
        ],
        'jam_pulang' => [
            'required' => 'Jam pulang harus diisi',
            'valid_date' => 'Format jam tidak valid',
        ],
        'batas_alpha' => [
            'required' => 'Batas alpha harus diisi',
            'valid_date' => 'Format jam tidak valid',
        ],
    ];

    /**
     * Ambil pengaturan utama
     */
    public function getPengaturan()
    {
        return $this->first() ?? [
            'jam_masuk'       => '07:00:00',
            'batas_terlambat' => '08:00:00',
            'jam_pulang'      => '15:00:00',
            'batas_alpha'     => '10:00:00',
        ];
    }

    /**
     * Cek status absensi berdasarkan jam masuk
     */
    public function statusAbsenMasuk($jamMasuk)
    {
        $pengaturan = $this->getPengaturan();
        $jamMasukTime = strtotime($jamMasuk);
        $batasTerlambat = strtotime($pengaturan['batas_terlambat']);

        if ($jamMasukTime <= $batasTerlambat) {
            return 'Tepat Waktu';
        }
        return 'Terlambat';
    }

    /**
     * Cek apakah sudah melewati batas alpha
     */
    public function sudahMelewatiBatasAlpha()
    {
        $pengaturan = $this->getPengaturan();
        $jamSekarang = strtotime(date('H:i:s'));
        $batasAlpha = strtotime($pengaturan['batas_alpha']);

        return $jamSekarang > $batasAlpha;
    }

    /**
     * Cek apakah jam pulang sudah tiba
     */
    public function jamPulanSudahTiba()
    {
        $pengaturan = $this->getPengaturan();
        $jamSekarang = strtotime(date('H:i:s'));
        $jamPulang = strtotime($pengaturan['jam_pulang']);

        return $jamSekarang >= $jamPulang;
    }
}
