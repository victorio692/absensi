<?php

namespace App\Models;

use CodeIgniter\Model;

class PelanggaranModel extends Model
{
    protected $table = 'pelanggaran';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['siswa_id', 'bulan', 'jenis', 'jumlah'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil pelanggaran siswa per bulan
     */
    public function getPelanggaranSiswa($siswaId, $bulan = null)
    {
        $query = $this->select('pelanggaran.*, siswa.nis, users.nama, users.username')
                      ->join('siswa', 'siswa.id = pelanggaran.siswa_id')
                      ->join('users', 'users.id = siswa.user_id')
                      ->where('pelanggaran.siswa_id', $siswaId);

        if ($bulan) {
            $query->where('pelanggaran.bulan', $bulan);
        }

        return $query->orderBy('pelanggaran.bulan', 'DESC')
                     ->orderBy('pelanggaran.jenis')
                     ->findAll();
    }

    /**
     * Ambil semua pelanggaran (untuk admin)
     */
    public function getAllPelanggaran($bulan = null)
    {
        $query = $this->select('pelanggaran.*, siswa.nis, siswa.kelas, users.nama, users.username')
                      ->join('siswa', 'siswa.id = pelanggaran.siswa_id')
                      ->join('users', 'users.id = siswa.user_id');

        if ($bulan) {
            $query->where('pelanggaran.bulan', $bulan);
        }

        return $query->orderBy('pelanggaran.bulan', 'DESC')
                     ->orderBy('siswa.kelas')
                     ->orderBy('users.nama')
                     ->findAll();
    }

    /**
     * Cek pelanggaran berdasarkan siswa & bulan & jenis
     */
    public function getPelanggaran($siswaId, $bulan, $jenis)
    {
        return $this->where('siswa_id', $siswaId)
                    ->where('bulan', $bulan)
                    ->where('jenis', $jenis)
                    ->first();
    }

    /**
     * Tambah atau update pelanggaran
     */
    public function tambahPelanggaran($siswaId, $jenis)
    {
        $bulanIni = date('Y-m-01'); // First day of current month

        $pelanggaran = $this->getPelanggaran($siswaId, $bulanIni, $jenis);

        if ($pelanggaran) {
            // Update jumlah
            return $this->update($pelanggaran['id'], [
                'jumlah' => $pelanggaran['jumlah'] + 1,
            ]);
        } else {
            // Insert baru
            return $this->insert([
                'siswa_id' => $siswaId,
                'bulan'    => $bulanIni,
                'jenis'    => $jenis,
                'jumlah'   => 1,
            ]);
        }
    }

    /**
     * Ambil total pelanggaran siswa per jenis
     */
    public function getTotalPelanggaranSiswa($siswaId, $bulan = null)
    {
        $query = $this->where('siswa_id', $siswaId);

        if ($bulan) {
            $query->where('bulan', $bulan);
        }

        $result = [];
        $pelanggaran = $query->findAll();

        foreach ($pelanggaran as $p) {
            $result[$p['jenis']] = ($result[$p['jenis']] ?? 0) + $p['jumlah'];
        }

        return $result;
    }

    /**
     * Reset pelanggaran per bulan (untuk maintenance)
     */
    public function resetBulanLalu()
    {
        $bulanLalu = date('Y-m-01', strtotime('-1 month'));
        return $this->where('bulan <', $bulanLalu)->delete();
    }
}
