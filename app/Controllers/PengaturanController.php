<?php

namespace App\Controllers;

use App\Models\PengaturanModel;
use CodeIgniter\Controller;

class PengaturanController extends Controller
{
    protected $helpers = ['absensi_helper'];
    protected $pengaturanModel;

    public function __construct()
    {
        $this->pengaturanModel = new PengaturanModel();
    }

    /**
     * Tampilkan pengaturan jam sekolah (Admin)
     */
    public function index()
    {
        // Proteksi: hanya admin
        if (session()->get('user_role') !== 'admin') {
            return redirect()->to('/siswa/dashboard')->with('error', 'Akses ditolak');
        }

        $pengaturan = $this->pengaturanModel->getPengaturan();

        $data = [
            'title'      => 'Pengaturan Jam Sekolah',
            'pengaturan' => $pengaturan,
        ];

        return view('admin/pengaturan/index', $data);
    }

    /**
     * Proses update pengaturan (Admin)
     */
    public function update()
    {
        // Proteksi
        if (session()->get('user_role') !== 'admin') {
            return redirect()->to('/siswa/dashboard')->with('error', 'Akses ditolak');
        }

        // Validasi
        if (!$this->pengaturanModel->validate($this->request->getPost())) {
            return redirect()->back()
                            ->withInput()
                            ->with('errors', $this->pengaturanModel->errors());
        }

        // Update pengaturan
        $this->pengaturanModel->update(1, [
            'jam_masuk'       => $this->request->getPost('jam_masuk'),
            'batas_terlambat' => $this->request->getPost('batas_terlambat'),
            'jam_pulang'      => $this->request->getPost('jam_pulang'),
            'batas_alpha'     => $this->request->getPost('batas_alpha'),
        ]);

        return redirect()->to('/admin/pengaturan')
                        ->with('success', 'Pengaturan jam sekolah berhasil diubah');
    }
}
