<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class SiswaController extends Controller
{
    protected $helpers = ['absensi_helper'];

    /**
     * Tampilkan daftar siswa (Admin)
     */
    public function index()
    {
        $siswaModel = new SiswaModel();
        $data = [
            'title'  => 'Daftar Siswa',
            'siswa'  => $siswaModel->getSiswaWithUser(),
        ];

        return view('admin/siswa/index', $data);
    }



    /**
     * Form edit siswa (Admin)
     */
    public function edit($id)
    {
        $siswaModel = new SiswaModel();
        $siswa = $siswaModel->getSiswaDetail($id);

        if (!$siswa) {
            return redirect()->to('/admin/siswa')->with('error', 'Siswa tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Siswa',
            'siswa' => $siswa,
        ];

        return view('admin/siswa/edit', $data);
    }

    /**
     * Proses update siswa (Admin)
     */
    public function update($id)
    {
        $userModel = new UserModel();
        $siswaModel = new SiswaModel();

        $siswa = $siswaModel->find($id);
        if (!$siswa) {
            return redirect()->to('/admin/siswa')->with('error', 'Siswa tidak ditemukan');
        }

        // Update user
        $userModel->update($siswa['user_id'], [
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
        ]);

        // Update siswa
        $siswaModel->update($id, [
            'nis'   => $this->request->getPost('nis'),
            'kelas' => $this->request->getPost('kelas'),
        ]);

        return redirect()->to('/admin/siswa')->with('success', 'Siswa berhasil diupdate');
    }

    /**
     * Hapus siswa (Admin)
     */
    public function delete($id)
    {
        $siswaModel = new SiswaModel();
        $userModel = new UserModel();

        $siswa = $siswaModel->find($id);
        if (!$siswa) {
            return redirect()->to('/admin/siswa')->with('error', 'Siswa tidak ditemukan');
        }

        // Hapus dari database (cascade delete juga menghapus absensi)
        $siswaModel->delete($id);
        $userModel->delete($siswa['user_id']);

        return redirect()->to('/admin/siswa')->with('success', 'Siswa berhasil dihapus');
    }


}
