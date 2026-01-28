<?php

namespace App\Controllers;

use App\Models\PelanggaranModel;
use App\Models\SiswaModel;
use CodeIgniter\Controller;

class PelanggaranController extends Controller
{
    protected $helpers = ['absensi_helper'];
    protected $pelanggaranModel;
    protected $siswaModel;

    public function __construct()
    {
        $this->pelanggaranModel = new PelanggaranModel();
        $this->siswaModel = new SiswaModel();
    }

    /**
     * Daftar pelanggaran semua siswa (Admin)
     */
    public function index()
    {
        // Proteksi: hanya admin
        if (session()->get('user_role') !== 'admin') {
            return redirect()->to('/siswa/dashboard')->with('error', 'Akses ditolak');
        }

        $bulan = $this->request->getGet('bulan') ?? date('Y-m-01');
        $pelanggaran = $this->pelanggaranModel->getAllPelanggaran($bulan);

        $data = [
            'title'        => 'Riwayat Pelanggaran Absensi',
            'pelanggaran'  => $pelanggaran,
            'bulanTerpilih' => $bulan,
        ];

        return view('admin/pelanggaran/index', $data);
    }

    /**
     * Pelanggaran siswa (Siswa melihat sendiri)
     */
    public function siswa()
    {
        // Proteksi: hanya siswa
        if (session()->get('user_role') !== 'siswa') {
            return redirect()->to('/admin/dashboard')->with('error', 'Akses ditolak');
        }

        $userId = session()->get('user_id');
        $siswaData = $this->siswaModel->where('user_id', $userId)->first();

        if (!$siswaData) {
            return redirect()->to('/logout')->with('error', 'Data siswa tidak ditemukan');
        }

        $bulan = $this->request->getGet('bulan') ?? date('Y-m-01');
        $pelanggaran = $this->pelanggaranModel->getPelanggaranSiswa($siswaData['id'], $bulan);
        $totalPelanggaran = $this->pelanggaranModel->getTotalPelanggaranSiswa($siswaData['id'], $bulan);

        $data = [
            'title'              => 'Riwayat Pelanggaran Absensi',
            'pelanggaran'        => $pelanggaran,
            'bulanTerpilih'      => $bulan,
            'totalPelanggaran'   => $totalPelanggaran,
        ];

        return view('siswa/pelanggaran', $data);
    }
}
