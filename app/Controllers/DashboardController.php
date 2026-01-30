<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\AbsensiModel;
use App\Helpers\CalendarHelper;
use CodeIgniter\Controller;

class DashboardController extends Controller
{
    /**
     * Dashboard Admin
     */
    public function admin()
    {
        $siswaModel = new SiswaModel();
        $absensiModel = new AbsensiModel();

        // Cek dan buat Alpha otomatis
        $absensiModel->checkAlphaOtomatis();

        $data = [
            'title'           => 'Dashboard Admin',
            'totalSiswa'      => $siswaModel->countAll(),
            'absensiHariIni'  => $absensiModel->where('DATE(tanggal)', date('Y-m-d'))->countAllResults(),
            'totalAbsensi'    => $absensiModel->countAll(),
            'recentAbsensi'   => $absensiModel->getAbsensiWithSiswa([])->limit(5)->findAll(),
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Dashboard Siswa
     */
    public function siswa()
    {
        $siswaModel = new SiswaModel();
        $absensiModel = new AbsensiModel();

        $siswaId = session()->get('user_id');
        
        // Cari siswa berdasarkan user_id dengan join ke users table
        $siswaData = $siswaModel->select('siswa.id, siswa.user_id, siswa.nisn, siswa.nis, siswa.kelas, siswa.created_at, siswa.updated_at, users.nama, users.username')
                                ->join('users', 'users.id = siswa.user_id')
                                ->where('siswa.user_id', $siswaId)
                                ->first();

        if (!$siswaData) {
            return redirect()->to('/logout')->with('error', 'Data siswa tidak ditemukan');
        }

        // Cek status absensi hari ini
        $absensiMasukHariIni = $absensiModel->checkAbsenMasukHariIni($siswaData['id']);
        $absensiPulangHariIni = $absensiModel->checkAbsenPulangHariIni($siswaData['id']);

        // Ambil riwayat absensi bulan ini
        $riwayatAbsensi = $absensiModel->getRiwayatSiswa($siswaData['id']);

        // Generate calendar data untuk bulan ini
        $calendarData = CalendarHelper::generateCalendarData($siswaData['id']);

        $data = [
            'title'                 => 'Dashboard Siswa',
            'siswa'                 => $siswaData,
            'sudahAbsenMasuk'       => !empty($absensiMasukHariIni),
            'sudahAbsenPulang'      => !empty($absensiPulangHariIni),
            'absensiMasukHariIni'   => $absensiMasukHariIni,
            'absensiPulangHariIni'  => $absensiPulangHariIni,
            'riwayatAbsensi'        => $riwayatAbsensi,
            'calendarData'          => $calendarData,
        ];

        return view('siswa/dashboard', $data);
    }
}
