<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table = 'absensi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['siswa_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'status'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Cek apakah siswa sudah absen masuk hari ini
     */
    public function checkAbsenMasukHariIni($siswaId)
    {
        return $this->where('siswa_id', $siswaId)
                    ->where('DATE(tanggal)', date('Y-m-d'))
                    ->where('jam_masuk IS NOT NULL')
                    ->first();
    }

    /**
     * Cek apakah siswa sudah absen pulang hari ini
     */
    public function checkAbsenPulangHariIni($siswaId)
    {
        return $this->where('siswa_id', $siswaId)
                    ->where('DATE(tanggal)', date('Y-m-d'))
                    ->where('jam_pulang IS NOT NULL')
                    ->first();
    }

    /**
     * Ambil absensi dengan relasi siswa dan user (return query builder)
     */
    public function getAbsensiWithSiswa($filters = [])
    {
        $query = $this->select('absensi.id, absensi.siswa_id, absensi.tanggal, absensi.jam_masuk, absensi.jam_pulang, absensi.status, absensi.created_at, absensi.updated_at, siswa.nis, siswa.kelas, users.nama')
                      ->join('siswa', 'siswa.id = absensi.siswa_id')
                      ->join('users', 'users.id = siswa.user_id');

        if (!empty($filters['siswa_id'])) {
            $query->where('absensi.siswa_id', $filters['siswa_id']);
        }

        if (!empty($filters['kelas'])) {
            $query->where('siswa.kelas', $filters['kelas']);
        }

        if (!empty($filters['start_date'])) {
            $query->where('DATE(absensi.tanggal) >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('DATE(absensi.tanggal) <=', $filters['end_date']);
        }

        return $query->orderBy('absensi.tanggal', 'DESC')
                     ->orderBy('absensi.jam_masuk', 'DESC');
    }

    /**
     * Tentukan status berdasarkan jam masuk
     */
    public static function tentakanStatus($jamMasuk)
    {
        $jamMasukObj = strtotime($jamMasuk);
        $jam0730 = strtotime('07:30:00');

        return $jamMasukObj <= $jam0730 ? 'Hadir' : 'Terlambat';
    }

    /**
     * Ambil riwayat absensi siswa (untuk siswa)
     */
    public function getRiwayatSiswa($siswaId, $bulan = null, $tahun = null)
    {
        if (!$bulan) {
            $bulan = date('m');
        }
        if (!$tahun) {
            $tahun = date('Y');
        }

        return $this->where('siswa_id', $siswaId)
                    ->where('MONTH(tanggal)', $bulan)
                    ->where('YEAR(tanggal)', $tahun)
                    ->orderBy('tanggal', 'ASC')
                    ->findAll();
    }

    /**
     * Cek dan buat Alpha otomatis jika melewati batas jam
     */
    public function checkAlphaOtomatis()
    {
        $pengaturanModel = new PengaturanModel();
        $izinModel = new IzinModel();
        $siswaModel = new SiswaModel();

        // Cek apakah sudah melewati batas alpha
        if (!$pengaturanModel->sudahMelewatiBatasAlpha()) {
            return;
        }

        $hariIni = date('Y-m-d');

        // Ambil semua siswa
        $allSiswa = $siswaModel->findAll();

        foreach ($allSiswa as $siswa) {
            // Cek apakah sudah ada absensi masuk
            $absensiMasuk = $this->where('siswa_id', $siswa['id'])
                                 ->where('tanggal', $hariIni)
                                 ->where('jam_masuk IS NOT NULL')
                                 ->first();

            // Cek apakah ada izin disetujui
            $izinDisetujui = $izinModel->getIzinDisetujui($siswa['id'], $hariIni);

            // Jika tidak ada absensi masuk dan tidak ada izin, buat alpha
            if (!$absensiMasuk && !$izinDisetujui) {
                $existing = $this->where('siswa_id', $siswa['id'])
                                 ->where('tanggal', $hariIni)
                                 ->first();

                if (!$existing) {
                    $this->insert([
                        'siswa_id'   => $siswa['id'],
                        'tanggal'    => $hariIni,
                        'jam_masuk'  => null,
                        'jam_pulang' => null,
                        'status'     => 'Alpha',
                    ]);
                } else {
                    // Update status menjadi Alpha
                    $this->update($existing['id'], ['status' => 'Alpha']);
                }

                // Tambah pelanggaran
                $pelanggaranModel = new PelanggaranModel();
                $pelanggaranModel->tambahPelanggaran($siswa['id'], 'Alpha');
            }
        }
    }

    /**
     * Tambah pelanggaran terlambat
     */
    public function tambahPelanggaranTerlambat($siswaId)
    {
        $pelanggaranModel = new PelanggaranModel();
        $pelanggaranModel->tambahPelanggaran($siswaId, 'Terlambat');
    }

    /**
     * Cek absensi hari ini untuk siswa (digunakan di dashboard siswa)
     */
    public function getAbsensiHariIni($siswaId)
    {
        $hariIni = date('Y-m-d');

        return $this->select('absensi.id, absensi.siswa_id, absensi.tanggal, absensi.jam_masuk, absensi.jam_pulang, absensi.status')
                    ->where('siswa_id', $siswaId)
                    ->where('tanggal', $hariIni)
                    ->first();
    }
}
