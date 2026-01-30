<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\AbsensiModel;
use App\Models\QrDailyModel;
use App\Models\PengaturanModel;
use CodeIgniter\Controller;

class AbsensiController extends Controller
{
    protected $helpers = ['AbsensiHelper'];
    protected $qrDailyModel;
    protected $pengaturanModel;

    public function __construct()
    {
        $this->qrDailyModel = new QrDailyModel();
        $this->pengaturanModel = new PengaturanModel();
    }

    /**
     * Form scan QR Code (Siswa) - ABSEN MASUK
     */
    public function scanMasuk()
    {
        $siswaId = $this->getSiswaIdFromSession();

        $data = [
            'title'   => 'Scan QR - Absen Masuk',
            'siswaId' => $siswaId,
        ];

        return view('siswa/qr_scan', $data);
    }

    /**
     * Proses scan QR untuk absen masuk
     */
    public function prosesScan()
    {
        $qrContent = trim($this->request->getPost('qr_content'));

        if (empty($qrContent)) {
            return redirect()->back()->with('error', 'QR Code tidak valid');
        }

        $siswaId = $this->getSiswaIdFromSession();
        $absensiModel = new AbsensiModel();

        // Validasi QR
        $validation = $this->qrDailyModel->validateQr($qrContent);

        if (!$validation['valid']) {
            return redirect()->back()->with('error', $validation['message']);
        }

        $locationId = $validation['location_id'];
        $status = $validation['status'];
        $tanggal = date('Y-m-d');

        // Cek apakah sudah absen masuk hari ini
        $existing = $absensiModel->where('siswa_id', $siswaId)
                                 ->where('tanggal', $tanggal)
                                 ->where('jam_masuk IS NOT NULL')
                                 ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah absen masuk hari ini pukul ' . date('H:i', strtotime($existing['jam_masuk'])));
        }

        // Cek jam masuk - jangan boleh duluan jam masuk
        $pengaturan = $this->pengaturanModel->getPengaturan();
        $jamSekarang = date('H:i:s');
        $jamMasuk = $pengaturan['jam_masuk'];

        if ($jamSekarang < $jamMasuk) {
            return redirect()->back()->with('error', 'Jam masuk belum tiba (mulai pukul ' . date('H:i', strtotime($jamMasuk)) . ')');
        }

        // Simpan absensi
        $absensiModel->insert([
            'siswa_id'   => $siswaId,
            'tanggal'    => $tanggal,
            'jam_masuk'  => $jamSekarang,
            'status'     => $status,
            'location_id' => $locationId,
        ]);

        $message = 'Absen masuk berhasil! Status: ' . $status;
        if ($status === 'Terlambat') {
            $message .= ' (sudah melewati jam masuk)';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Form absen pulang (Siswa) - TANPA QR
     */
    public function absenPulang()
    {
        $siswaId = $this->getSiswaIdFromSession();
        $absensiModel = new AbsensiModel();
        $tanggal = date('Y-m-d');

        // Cek apakah sudah absen masuk
        $absenMasuk = $absensiModel->where('siswa_id', $siswaId)
                                   ->where('tanggal', $tanggal)
                                   ->first();

        if (!$absenMasuk) {
            return redirect()->back()->with('error', 'Anda belum absen masuk hari ini');
        }

        if ($absenMasuk['jam_pulang']) {
            return redirect()->back()->with('error', 'Anda sudah absen pulang hari ini');
        }

        // Cek jam pulang
        $pengaturan = $this->pengaturanModel->getPengaturan();
        $jamSekarang = date('H:i:s');
        $jamPulang = $pengaturan['jam_pulang'];

        if ($jamSekarang < $jamPulang) {
            return redirect()->back()->with('error', 'Jam pulang belum tiba (mulai pukul ' . date('H:i', strtotime($jamPulang)) . ')');
        }

        // Update jam pulang
        $absensiModel->update($absenMasuk['id'], [
            'jam_pulang' => $jamSekarang,
        ]);

        return redirect()->back()->with('success', 'Absen pulang berhasil pukul ' . date('H:i'));
    }

    /**
     * Proses absensi dari QR Code (Legacy - OLD)
     */
    public function prosesAbsen()
    {
        $qrToken = trim($this->request->getPost('qr_token'));

        if (empty($qrToken)) {
            return redirect()->back()->with('error', 'QR Code tidak valid');
        }

        $siswaModel = new SiswaModel();
        $absensiModel = new AbsensiModel();

        // Validasi QR Token
        $siswa = $siswaModel->findByQRToken($qrToken);
        if (!$siswa) {
            return redirect()->back()->with('error', 'QR Code tidak valid atau terdaftar');
        }

        // Cek apakah sudah absen masuk hari ini
        $absenMasukHariIni = $absensiModel->checkAbsenMasukHariIni($siswa['id']);

        // Jika belum absen masuk, lakukan absen masuk
        if (!$absenMasukHariIni) {
            $jamMasuk = date('H:i:s');
            $status = AbsensiModel::tentakanStatus($jamMasuk);

            $absensiModel->insert([
                'siswa_id'   => $siswa['id'],
                'tanggal'    => date('Y-m-d'),
                'jam_masuk'  => $jamMasuk,
                'status'     => $status,
            ]);

            return redirect()->back()->with('success', 'Absen masuk berhasil! Status: ' . $status);
        }

        // Jika sudah absen masuk, lakukan absen pulang
        $absenPulangHariIni = $absensiModel->checkAbsenPulangHariIni($siswa['id']);
        if ($absenPulangHariIni) {
            return redirect()->back()->with('error', 'Anda sudah absen pulang hari ini');
        }

        // Update jam pulang
        $absensiModel->update($absenMasukHariIni['id'], [
            'jam_pulang' => date('H:i:s'),
        ]);


        return redirect()->back()->with('success', 'Absen pulang berhasil!');
    }

    /**
     * Riwayat absensi siswa
     */
    public function riwayat()
    {
        $siswaModel = new SiswaModel();
        $absensiModel = new AbsensiModel();

        $siswaId = $this->getSiswaIdFromSession();
        $siswa = $siswaModel->find($siswaId);

        if (!$siswa) {
            return redirect()->to('/logout')->with('error', 'Data siswa tidak ditemukan');
        }

        // Filter bulan dan tahun
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $riwayat = $absensiModel->getRiwayatSiswa($siswa['id'], $bulan, $tahun);

        // Hitung statistik
        $hadir = count(array_filter($riwayat, fn($a) => $a['status'] === 'Hadir'));
        $terlambat = count(array_filter($riwayat, fn($a) => $a['status'] === 'Terlambat'));

        $data = [
            'title'      => 'Riwayat Absensi',
            'siswa'      => $siswa,
            'riwayat'    => $riwayat,
            'bulan'      => $bulan,
            'tahun'      => $tahun,
            'hadir'      => $hadir,
            'terlambat'  => $terlambat,
            'total'      => count($riwayat),
        ];

        return view('siswa/riwayat', $data);
    }

    /**
     * Daftar absensi (Admin)
     */
    public function index()
    {
        $absensiModel = new AbsensiModel();
        $siswaModel = new SiswaModel();

        // Filter
        $filters = [
            'siswa_id'  => $this->request->getGet('siswa_id'),
            'kelas'     => $this->request->getGet('kelas'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
        ];

        // Ambil data absensi
        $absensiList = $absensiModel->getAbsensiWithSiswa($filters)->findAll();

        // Ambil daftar kelas unik
        $kelasList = $siswaModel->distinct()->select('kelas')->findAll();

        // Ambil daftar siswa
        $siswaList = $siswaModel->getSiswaWithUser();

        $data = [
            'title'       => 'Data Absensi',
            'absensi'     => $absensiList,
            'filters'     => $filters,
            'siswaList'   => $siswaList,
            'kelasList'   => $kelasList,
        ];

        return view('admin/absensi/index', $data);
    }

    /**
     * Export Absensi ke PDF (Generate HTML Printable)
     */
    public function exportPDF()
    {
        $absensiModel = new AbsensiModel();

        // Filter
        $filters = [
            'siswa_id'  => $this->request->getGet('siswa_id'),
            'kelas'     => $this->request->getGet('kelas'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
        ];

        // Ambil data absensi
        $absensiList = $absensiModel->getAbsensiWithSiswa($filters)->findAll();

        $data = [
            'title'    => 'Laporan Absensi',
            'absensi'  => $absensiList,
            'filters'  => $filters,
            'print_date' => date('d-m-Y H:i:s'),
        ];

        return view('admin/absensi/export_pdf', $data);
    }

    /**
     * Helper: Ambil siswa ID dari session
     */
    private function getSiswaIdFromSession()
    {
        $siswaModel = new SiswaModel();
        $userId = session()->get('user_id');
        $siswa = $siswaModel->where('user_id', $userId)->first();
        return $siswa['id'] ?? null;
    }
}
