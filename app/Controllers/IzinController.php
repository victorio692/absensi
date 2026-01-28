<?php

namespace App\Controllers;

use App\Models\IzinModel;
use App\Models\SiswaModel;
use CodeIgniter\Controller;

class IzinController extends Controller
{
    protected $helpers = ['absensi_helper', 'filesystem'];
    protected $izinModel;
    protected $siswaModel;

    public function __construct()
    {
        $this->izinModel = new IzinModel();
        $this->siswaModel = new SiswaModel();
    }

    /**
     * Daftar izin siswa (Siswa)
     */
    public function index()
    {
        // Proteksi: hanya siswa yang bisa akses
        if (session()->get('user_role') !== 'siswa') {
            return redirect()->to('/admin/dashboard')->with('error', 'Akses ditolak');
        }

        $userId = session()->get('user_id');
        $siswaData = $this->siswaModel->where('user_id', $userId)->first();

        if (!$siswaData) {
            return redirect()->to('/logout')->with('error', 'Data siswa tidak ditemukan');
        }

        $izin = $this->izinModel->getIzinWithSiswa($siswaData['id'])->findAll();

        $data = [
            'title' => 'Riwayat Izin/Sakit',
            'izin'  => $izin,
        ];

        return view('izin/index', $data);
    }

    /**
     * Form ajukan izin (Siswa)
     */
    public function create()
    {
        // Proteksi: hanya siswa yang bisa akses
        if (session()->get('user_role') !== 'siswa') {
            return redirect()->to('/admin/dashboard')->with('error', 'Akses ditolak');
        }

        $data = [
            'title' => 'Ajukan Izin/Sakit',
        ];

        return view('izin/create', $data);
    }

    /**
     * Proses ajukan izin (Siswa)
     */
    public function store()
    {
        // Proteksi
        if (session()->get('user_role') !== 'siswa') {
            return redirect()->to('/admin/dashboard')->with('error', 'Akses ditolak');
        }

        $userId = session()->get('user_id');
        $siswaData = $this->siswaModel->where('user_id', $userId)->first();

        if (!$siswaData) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
        }

        $tanggal = $this->request->getPost('tanggal');

        // Cek apakah bisa ajukan izin
        if (!$this->izinModel->canSubmitIzin($siswaData['id'], $tanggal)) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Izin hanya bisa diajukan untuk hari ini atau kemarin');
        }

        // Validasi
        if (!$this->izinModel->validate($this->request->getPost())) {
            return redirect()->back()
                            ->withInput()
                            ->with('errors', $this->izinModel->errors());
        }

        // Handle file upload
        $fileBukti = $this->request->getFile('file_bukti');
        $filenameBukti = null;

        if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
            $filenameBukti = $fileBukti->getRandomName();
            $fileBukti->move(ROOTPATH . 'public/uploads/izin', $filenameBukti);
        }

        // Insert izin
        $this->izinModel->insert([
            'siswa_id'   => $siswaData['id'],
            'tanggal'    => $tanggal,
            'jenis'      => $this->request->getPost('jenis'),
            'keterangan' => $this->request->getPost('keterangan'),
            'file_bukti' => $filenameBukti,
            'status'     => 'Menunggu',
        ]);

        return redirect()->to('/izin')
                        ->with('success', 'Izin berhasil diajukan. Menunggu verifikasi admin.');
    }

    /**
     * Verifikasi izin (Admin)
     */
    public function verifikasi($id)
    {
        // Proteksi: hanya admin yang bisa akses
        if (session()->get('user_role') !== 'admin') {
            return redirect()->to('/siswa/dashboard')->with('error', 'Akses ditolak');
        }

        $izin = $this->izinModel->find($id);

        if (!$izin) {
            return redirect()->back()->with('error', 'Izin tidak ditemukan');
        }

        $data = [
            'title' => 'Verifikasi Izin',
            'izin'  => $izin,
        ];

        return view('izin/verifikasi', $data);
    }

    /**
     * Proses verifikasi izin (Admin)
     */
    public function updateStatus($id)
    {
        // Proteksi
        if (session()->get('user_role') !== 'admin') {
            return redirect()->to('/siswa/dashboard')->with('error', 'Akses ditolak');
        }

        $izin = $this->izinModel->find($id);

        if (!$izin) {
            return redirect()->back()->with('error', 'Izin tidak ditemukan');
        }

        $status = $this->request->getPost('status');

        if (!in_array($status, ['Disetujui', 'Ditolak'])) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        // Update status izin
        $this->izinModel->update($id, ['status' => $status]);

        // Jika disetujui, buat record absensi dengan status Izin
        if ($status === 'Disetujui') {
            $this->buatAbsensiIzin($izin);
        }

        return redirect()->to('/admin/izin')
                        ->with('success', "Izin berhasil di{$status}");
    }

    /**
     * Buat record absensi ketika izin disetujui
     */
    private function buatAbsensiIzin($izin)
    {
        $absensiModel = new \App\Models\AbsensiModel();

        // Cek apakah sudah ada absensi
        $existing = $absensiModel->where('siswa_id', $izin['siswa_id'])
                                 ->where('tanggal', $izin['tanggal'])
                                 ->first();

        if (!$existing) {
            $absensiModel->insert([
                'siswa_id'   => $izin['siswa_id'],
                'tanggal'    => $izin['tanggal'],
                'jam_masuk'  => null,
                'jam_pulang' => null,
                'status'     => 'Izin',
            ]);
        } else {
            // Update status menjadi Izin
            $absensiModel->update($existing['id'], ['status' => 'Izin']);
        }
    }

    /**
     * Daftar izin untuk admin (verifikasi)
     */
    public function admin()
    {
        // Proteksi: hanya admin
        if (session()->get('user_role') !== 'admin') {
            return redirect()->to('/siswa/dashboard')->with('error', 'Akses ditolak');
        }

        $izinMenunggu = $this->izinModel->getIzinMenunggu();

        $data = [
            'title'         => 'Verifikasi Izin/Sakit',
            'izinMenunggu'  => $izinMenunggu,
        ];

        return view('admin/izin/verifikasi', $data);
    }

    /**
     * Hapus izin (Siswa - hanya yang status Menunggu)
     */
    public function delete($id)
    {
        // Proteksi
        if (session()->get('user_role') !== 'siswa') {
            return redirect()->to('/admin/dashboard')->with('error', 'Akses ditolak');
        }

        $userId = session()->get('user_id');
        $siswaData = $this->siswaModel->where('user_id', $userId)->first();

        $izin = $this->izinModel->find($id);

        if (!$izin || $izin['siswa_id'] != $siswaData['id']) {
            return redirect()->back()->with('error', 'Izin tidak ditemukan');
        }

        if ($izin['status'] !== 'Menunggu') {
            return redirect()->back()->with('error', 'Hanya izin dengan status Menunggu yang bisa dihapus');
        }

        // Hapus file jika ada
        if ($izin['file_bukti'] && file_exists(ROOTPATH . 'public/uploads/izin/' . $izin['file_bukti'])) {
            unlink(ROOTPATH . 'public/uploads/izin/' . $izin['file_bukti']);
        }

        // Hapus record
        $this->izinModel->delete($id);

        return redirect()->to('/izin')->with('success', 'Izin berhasil dihapus');
    }
}
