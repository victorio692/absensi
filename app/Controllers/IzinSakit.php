<?php

namespace App\Controllers;

use App\Models\IzinSakitModel;
use App\Models\AbsensiModel;

class IzinSakit extends BaseController
{
    protected $izinSakitModel;
    protected $absensiModel;

    public function __construct()
    {
        $this->izinSakitModel = new IzinSakitModel();
        $this->absensiModel = new AbsensiModel();
    }

    /**
     * Menampilkan form pengajuan izin/sakit
     */
    public function create()
    {
        // Check if user is student
        if (session()->get('user_role') !== 'siswa') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }

        $data = [
            'title' => 'Pengajuan Izin / Sakit',
            'siswa_id' => session()->get('user_id'),
        ];

        return view('siswa/izin_sakit/create', $data);
    }

    /**
     * Proses submit pengajuan izin/sakit
     */
    public function store()
    {
        if (session()->get('user_role') !== 'siswa') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }

        $siswa_id = session()->get('user_id');
        $tanggal = $this->request->getPost('tanggal');
        $jenis = $this->request->getPost('jenis');
        $alasan = $this->request->getPost('alasan');

        // Validasi tanggal tidak boleh di masa lalu
        if (strtotime($tanggal) < strtotime(date('Y-m-d'))) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tidak bisa mengajukan izin/sakit untuk hari yang sudah lewat');
        }

        // Validasi waktu: hanya bisa submit sebelum jam 07:00
        if ($tanggal === date('Y-m-d')) {
            $jamSekarang = date('H:i:s');
            if ($jamSekarang >= '07:00:00') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Pengajuan izin/sakit harus dilakukan sebelum jam 07:00 untuk hari ini');
            }
        }

        // Cek apakah sudah ada izin/sakit untuk tanggal yang sama
        $existing = $this->izinSakitModel->isAlreadySubmitted($siswa_id, $tanggal);
        if ($existing) {
            return redirect()->back()
                ->with('error', 'Anda sudah mengajukan izin/sakit untuk tanggal ' . $tanggal);
        }

        // Cek apakah sudah ada absensi untuk tanggal tersebut
        $existingAbsensi = $this->absensiModel
            ->where('siswa_id', $siswa_id)
            ->where('DATE(created_at)', 'DATE("' . $tanggal . '")')
            ->first();

        if ($existingAbsensi) {
            return redirect()->back()
                ->with('error', 'Anda sudah melakukan absensi untuk tanggal ' . $tanggal);
        }

        $data = [
            'siswa_id' => $siswa_id,
            'tanggal' => $tanggal,
            'jenis' => $jenis,
            'alasan' => $alasan,
            'status' => 'pending',
        ];

        // Handle file upload
        $file = $this->request->getFile('bukti_file');
        if ($file && $file->isValid()) {
            // Validate file
            if (!$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads/izin_sakit', $newName);
                $data['bukti_file'] = $newName;
            }
        }

        if ($this->izinSakitModel->insert($data)) {
            return redirect()->to('/siswa/izin-sakit-riwayat')
                ->with('success', 'Pengajuan izin/sakit berhasil diajukan. Menunggu persetujuan admin.');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengajukan izin/sakit. Coba lagi.');
        }
    }

    /**
     * Menampilkan riwayat pengajuan izin/sakit siswa
     */
    public function riwayat()
    {
        if (session()->get('user_role') !== 'siswa') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }

        $siswa_id = session()->get('user_id');

        $izinSakit = $this->izinSakitModel
            ->where('siswa_id', $siswa_id)
            ->orderBy('tanggal', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Riwayat Pengajuan Izin / Sakit',
            'izinSakit' => $izinSakit,
        ];

        return view('siswa/izin_sakit/riwayat', $data);
    }

    /**
     * Menampilkan detail pengajuan
     */
    public function detail($id)
    {
        if (session()->get('user_role') !== 'siswa') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }

        $siswa_id = session()->get('user_id');
        $izin = $this->izinSakitModel->find($id);

        if (!$izin || $izin['siswa_id'] != $siswa_id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Detail Pengajuan Izin / Sakit',
            'izin' => $izin,
        ];

        return view('siswa/izin_sakit/detail', $data);
    }

    /**
     * Download bukti file
     */
    public function downloadBukti($id)
    {
        if (session()->get('user_role') !== 'siswa') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }

        $siswa_id = session()->get('user_id');
        $izin = $this->izinSakitModel->find($id);

        if (!$izin || $izin['siswa_id'] != $siswa_id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (!$izin['bukti_file']) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        $file = WRITEPATH . 'uploads/izin_sakit/' . $izin['bukti_file'];

        if (!file_exists($file)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        return $this->response->download($file);
    }
}
