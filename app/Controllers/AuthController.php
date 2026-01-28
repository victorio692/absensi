<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected $helpers = [];

    /**
     * Tampilkan halaman login
     */
    public function index()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->has('user_id')) {
            return redirect()->to($this->getDashboardURL());
        }

        return view('auth/login');
    }

    /**
     * Proses login
     */
    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $userRole = $this->request->getPost('user_role') ?? 'admin'; // 'admin' atau 'siswa'

        // Validasi input
        if (empty($username) || empty($password)) {
            return redirect()->back()->with('error', 'Username/NISN dan password/NIS harus diisi');
        }

        $userModel = new UserModel();
        $user = null;

        if ($userRole === 'siswa') {
            // Login siswa: username = NISN, password = NIS
            $user = $userModel->findSiswaByNisn($username);
            
            if (!$user) {
                return redirect()->back()->with('error', 'NISN tidak ditemukan')->withInput();
            }

            // Validasi NIS sebagai password
            if ($user['nis'] !== $password) {
                return redirect()->back()->with('error', 'NIS salah')->withInput();
            }
        } else {
            // Login admin: username dan password biasa
            $user = $userModel->findByUsername($username);

            if (!$user || !password_verify($password, $user['password'])) {
                return redirect()->back()->with('error', 'Username atau password salah')->withInput();
            }
        }

        // Set session
        session()->set([
            'user_id'   => $user['id'],
            'user_name' => $user['nama'],
            'user_role' => $user['role'],
        ]);

        // Jika siswa, tambahkan siswa_id ke session
        if ($user['role'] === 'siswa' && isset($user['siswa_id'])) {
            session()->set('siswa_id', $user['siswa_id']);
        }

        // Redirect ke dashboard
        $dashboard = $user['role'] === 'admin' ? '/admin/dashboard' : '/siswa/dashboard';
        return redirect()->to($dashboard)->with('success', 'Login berhasil!');
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logout berhasil');
    }

    /**
     * Redirect home berdasarkan status login
     */
    public function redirectHome()
    {
        if (session()->has('user_id')) {
            $role = session()->get('user_role');
            if ($role === 'admin') {
                return redirect()->to('/admin/dashboard');
            }
            return redirect()->to('/siswa/dashboard');
        }
        return redirect()->to('/login');
    }

    /**
     * Dapatkan URL dashboard berdasarkan role
     */
    private function getDashboardURL()
    {
        $role = session()->get('user_role');
        return $role === 'admin' ? '/admin/dashboard' : '/siswa/dashboard';
    }

    /**
     * Tampilkan halaman registrasi siswa
     */
    public function register()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->has('user_id')) {
            return redirect()->to($this->getDashboardURL());
        }

        return view('auth/register');
    }

    /**
     * Proses registrasi siswa baru
     */
    public function processRegister()
    {
        $nisn = $this->request->getPost('nisn');
        $nis = $this->request->getPost('nis');
        $nama = $this->request->getPost('nama');
        $kelas = $this->request->getPost('kelas');

        // Validasi input
        if (empty($nisn) || empty($nis) || empty($nama) || empty($kelas)) {
            return redirect()->back()->with('error', 'Semua field harus diisi')->withInput();
        }

        $userModel = new UserModel();
        $siswaModel = new \App\Models\SiswaModel();

        // Cek apakah NISN sudah terdaftar
        $existingSiswa = $userModel->findSiswaByNisn($nisn);
        if ($existingSiswa) {
            return redirect()->back()->with('error', 'NISN sudah terdaftar di sistem')->withInput();
        }

        // Cek apakah NIS sudah terdaftar
        $checkNis = $siswaModel->where('nis', $nis)->first();
        if ($checkNis) {
            return redirect()->back()->with('error', 'NIS sudah terdaftar di sistem')->withInput();
        }

        // Buat user baru
        $userData = [
            'nama'     => $nama,
            'username' => strtolower(str_replace(' ', '.', $nama)) . '.' . substr($nisn, -4), // username otomatis
            'password' => password_hash($nis, PASSWORD_BCRYPT), // password default = NIS yang di-hash
            'role'     => 'siswa',
        ];

        $userId = $userModel->insert($userData);

        if (!$userId) {
            return redirect()->back()->with('error', 'Gagal membuat akun. Silakan coba lagi')->withInput();
        }

        // Buat record siswa (tanpa qr_token)
        $siswaData = [
            'user_id'   => $userId,
            'nisn'      => $nisn,
            'nis'       => $nis,
            'kelas'     => $kelas,
        ];

        if ($siswaModel->insert($siswaData)) {
            return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login dengan NISN dan NIS Anda');
        } else {
            // Rollback: hapus user yang baru dibuat
            $userModel->delete($userId);
            return redirect()->back()->with('error', 'Gagal menyimpan data siswa')->withInput();
        }
    }
}
