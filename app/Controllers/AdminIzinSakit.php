<?php

namespace App\Controllers;

use App\Models\IzinSakitModel;
use App\Models\AbsensiModel;

class AdminIzinSakit extends BaseController
{
    protected $izinSakitModel;
    protected $absensiModel;

    public function __construct()
    {
        $this->izinSakitModel = new IzinSakitModel();
        $this->absensiModel = new AbsensiModel();
    }

    /**
     * Menampilkan daftar pengajuan izin/sakit untuk admin
     */
    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }

        $filters = [
            'status' => $this->request->getGet('status') ?? '',
            'jenis' => $this->request->getGet('jenis') ?? '',
            'start_date' => $this->request->getGet('start_date') ?? '',
            'end_date' => $this->request->getGet('end_date') ?? '',
        ];

        $query = $this->izinSakitModel->getWithSiswa($filters);
        $izinSakitList = $query->findAll();

        // Hitung statistik
        $stats = [
            'pending' => $this->izinSakitModel->where('status', 'pending')->countAllResults(),
            'approved' => $this->izinSakitModel->where('status', 'approved')->countAllResults(),
            'rejected' => $this->izinSakitModel->where('status', 'rejected')->countAllResults(),
            'izin' => $this->izinSakitModel->where('jenis', 'izin')->countAllResults(),
            'sakit' => $this->izinSakitModel->where('jenis', 'sakit')->countAllResults(),
        ];

        $data = [
            'title' => 'Manajemen Izin & Sakit',
            'izinSakitList' => $izinSakitList,
            'stats' => $stats,
            'filters' => $filters,
        ];

        return view('admin/izin_sakit/index', $data);
    }

    /**
     * Menampilkan detail pengajuan untuk admin
     */
    public function detail($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }

        $izin = $this->izinSakitModel->select('izin_sakit.*, siswa.nama, siswa.kelas, siswa.nis')
            ->join('siswa', 'siswa.id = izin_sakit.siswa_id', 'left')
            ->where('izin_sakit.id', $id)
            ->first();

        if (!$izin) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Detail Pengajuan Izin / Sakit',
            'izin' => $izin,
        ];

        return view('admin/izin_sakit/detail', $data);
    }

    /**
     * Setujui pengajuan izin/sakit dan buat record absensi otomatis
     */
    public function approve($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }

        $izin = $this->izinSakitModel->find($id);

        if (!$izin) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $catatan = $this->request->getPost('catatan_admin') ?? '';

        // Update status izin/sakit
        $this->izinSakitModel->approve($id, $catatan);

        // Cek apakah sudah ada absensi untuk tanggal tersebut
        $existingAbsensi = $this->absensiModel
            ->where('siswa_id', $izin['siswa_id'])
            ->where('tanggal', $izin['tanggal'])
            ->first();

        // Jika belum ada, buat record absensi otomatis
        if (!$existingAbsensi) {
            $status = ($izin['jenis'] === 'izin') ? 'izin' : 'sakit';
            $this->absensiModel->insert([
                'siswa_id' => $izin['siswa_id'],
                'tanggal' => $izin['tanggal'],
                'status' => $status,
                'source' => 'izin',
                'keterangan' => ucfirst($izin['jenis']) . ' disetujui oleh admin',
            ]);
        }

        return redirect()->to('/admin/izin-sakit')
            ->with('success', 'Pengajuan izin/sakit berhasil disetujui');
    }

    /**
     * Tolak pengajuan izin/sakit
     */
    public function reject($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }

        $izin = $this->izinSakitModel->find($id);

        if (!$izin) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $catatan = $this->request->getPost('catatan_admin') ?? '';

        // Update status izin/sakit
        $this->izinSakitModel->reject($id, $catatan);

        return redirect()->to('/admin/izin-sakit')
            ->with('success', 'Pengajuan izin/sakit berhasil ditolak. Siswa wajib melakukan absensi.');
    }

    /**
     * Download bukti file
     */
    public function downloadBukti($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }

        $izin = $this->izinSakitModel->find($id);

        if (!$izin || !$izin['bukti_file']) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        $file = WRITEPATH . 'uploads/izin_sakit/' . $izin['bukti_file'];

        if (!file_exists($file)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        return $this->response->download($file);
    }

    /**
     * Hapus pengajuan izin/sakit
     */
    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }

        $izin = $this->izinSakitModel->find($id);

        if (!$izin) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // Hapus file jika ada
        if ($izin['bukti_file']) {
            $file = WRITEPATH . 'uploads/izin_sakit/' . $izin['bukti_file'];
            if (file_exists($file)) {
                unlink($file);
            }
        }

        // Hapus record izin/sakit
        $this->izinSakitModel->delete($id);

        // Hapus record absensi jika ada dan bersumber dari izin
        $this->absensiModel
            ->where('siswa_id', $izin['siswa_id'])
            ->where('tanggal', $izin['tanggal'])
            ->where('source', 'izin')
            ->delete();

        return redirect()->to('/admin/izin-sakit')
            ->with('success', 'Pengajuan izin/sakit berhasil dihapus');
    }
}
