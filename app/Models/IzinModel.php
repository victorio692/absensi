<?php

namespace App\Models;

use CodeIgniter\Model;

class IzinModel extends Model
{
    protected $table = 'izin';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['siswa_id', 'tanggal', 'jenis', 'keterangan', 'file_bukti', 'status'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Validasi
     */
    protected $validationRules = [
        'tanggal'     => 'required|valid_date[Y-m-d]',
        'jenis'       => 'required|in_list[izin,sakit]',
        'keterangan'  => 'required|min_length[10]',
        'file_bukti'  => 'uploaded[file_bukti]|mime_in[file_bukti,image/jpg,image/jpeg,image/gif,image/png,application/pdf]|max_size[file_bukti,5120]',
    ];

    protected $validationMessages = [
        'tanggal' => [
            'required'   => 'Tanggal harus diisi',
            'valid_date' => 'Format tanggal tidak valid',
        ],
        'jenis' => [
            'required' => 'Jenis izin/sakit harus dipilih',
            'in_list' => 'Jenis tidak valid',
        ],
        'keterangan' => [
            'required'   => 'Keterangan harus diisi',
            'min_length' => 'Keterangan minimal 10 karakter',
        ],
        'file_bukti' => [
            'uploaded'  => 'File bukti harus diunggah',
            'mime_in'   => 'File harus berupa JPG, PNG, GIF, atau PDF',
            'max_size'  => 'Ukuran file maksimal 5MB',
        ],
    ];

    /**
     * Ambil izin dengan relasi siswa & user
     */
    public function getIzinWithSiswa($siswaId = null)
    {
        $query = $this->select('izin.id, izin.siswa_id, izin.tanggal, izin.jenis, izin.keterangan, izin.file_bukti, izin.status, izin.created_at, izin.updated_at, siswa.nis, siswa.kelas, users.nama, users.username')
                      ->join('siswa', 'siswa.id = izin.siswa_id')
                      ->join('users', 'users.id = siswa.user_id');

        if ($siswaId) {
            $query->where('izin.siswa_id', $siswaId);
        }

        return $query->orderBy('izin.created_at', 'DESC');
    }

    /**
     * Cek apakah bisa ajukan izin untuk tanggal tertentu (H-0 atau H-1)
     */
    public function canSubmitIzin($siswaId, $tanggal)
    {
        $tanggalIzin = strtotime($tanggal);
        $hariIni = strtotime(date('Y-m-d'));
        $kemarin = strtotime('-1 day', $hariIni);

        // Izin hanya H-0 atau H-1
        if ($tanggalIzin != $hariIni && $tanggalIzin != $kemarin) {
            return false;
        }

        // Cek apakah sudah ada izin untuk hari itu
        $existing = $this->where('siswa_id', $siswaId)
                         ->where('tanggal', $tanggal)
                         ->first();

        return empty($existing);
    }

    /**
     * Ambil izin yang menunggu verifikasi (untuk admin)
     */
    public function getIzinMenunggu()
    {
        return $this->getIzinWithSiswa()
                    ->where('izin.status', 'Menunggu')
                    ->findAll();
    }

    /**
     * Cek ada izin disetujui untuk siswa di tanggal tertentu
     */
    public function getIzinDisetujui($siswaId, $tanggal)
    {
        return $this->where('siswa_id', $siswaId)
                    ->where('tanggal', $tanggal)
                    ->where('status', 'Disetujui')
                    ->first();
    }
}
