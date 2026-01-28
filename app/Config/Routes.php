<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ===============================================
// Auth Routes (Tanpa Filter)
// ===============================================
$routes->get('/login', 'AuthController::index');
$routes->post('/login', 'AuthController::login');
$routes->get('/register', 'AuthController::register');
$routes->post('/auth/processRegister', 'AuthController::processRegister');
$routes->get('/logout', 'AuthController::logout');

// ===============================================
// Redirect default ke login jika belum login
// ===============================================
$routes->get('/', 'AuthController::redirectHome');

// ===============================================
// Admin Routes (Dengan Filter Auth)
// ===============================================
$routes->group('admin', ['filter' => 'auth:admin'], static function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'DashboardController::admin');

    // Siswa Management (View/Edit/Delete Only - Create via Self-Registration)
    $routes->get('siswa', 'SiswaController::index');
    $routes->get('siswa/(:num)/edit', 'SiswaController::edit/$1');
    $routes->post('siswa/(:num)/update', 'SiswaController::update/$1');
    $routes->get('siswa/(:num)/delete', 'SiswaController::delete/$1');

    // Absensi (Admin View)
    $routes->get('absensi', 'AbsensiController::index');

    // QR Location Management (NEW)
    $routes->get('qr-location', 'QrLocation::index');
    $routes->get('qr-location/create', 'QrLocation::create');
    $routes->get('qr-location/debug-test', 'QrLocation::debugTest');
    $routes->post('qr-location/store', 'QrLocation::store');
    $routes->get('qr-location/(:num)/edit', 'QrLocation::edit/$1');
    $routes->post('qr-location/(:num)/update', 'QrLocation::update/$1');
    $routes->get('qr-location/(:num)/delete', 'QrLocation::delete/$1');
    $routes->get('qr-location/generate-daily', 'QrLocation::generateDaily');

    // QR Daily Management (NEW)
    $routes->get('qr-daily', 'QrDaily::index');
    $routes->post('qr-daily/generate', 'QrDaily::generate');
    $routes->get('qr-daily/(:num)/show', 'QrDaily::show/$1');
    $routes->get('qr-daily/(:num)/print', 'QrDaily::printQr/$1');
    $routes->post('api/qr/validate', 'QrDaily::validateQr');

    // Pengaturan Jam Sekolah
    $routes->get('pengaturan', 'PengaturanController::index');
    $routes->post('pengaturan/update', 'PengaturanController::update');

    // Verifikasi Izin/Sakit
    $routes->get('izin', 'IzinController::admin');
    $routes->get('izin/(:num)/verifikasi', 'IzinController::verifikasi/$1');
    $routes->post('izin/(:num)/updateStatus', 'IzinController::updateStatus/$1');

    // Riwayat Pelanggaran
    $routes->get('pelanggaran', 'PelanggaranController::index');
});

// ===============================================
// Siswa Routes (Dengan Filter Auth)
// ===============================================
$routes->group('siswa', ['filter' => 'auth:siswa'], static function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'DashboardController::siswa');

    // Absensi Scan (NEW - QR Dinamis)
    $routes->get('scan-masuk', 'AbsensiController::scanMasuk');
    $routes->post('scan-masuk/proses', 'AbsensiController::prosesScan');
    $routes->get('absen-pulang', 'AbsensiController::absenPulang');
    $routes->post('absen-pulang', 'AbsensiController::absenPulang');

    // Legacy Scan (Old QR Token)
    $routes->get('scan', 'AbsensiController::scan');
    $routes->post('scan/proses', 'AbsensiController::prosesAbsen');

    // Riwayat
    $routes->get('riwayat', 'AbsensiController::riwayat');

    // Pelanggaran
    $routes->get('pelanggaran', 'PelanggaranController::siswa');
});

// ===============================================
// Izin Routes (Siswa - Dengan Filter Auth)
// ===============================================
$routes->group('izin', ['filter' => 'auth:siswa'], static function ($routes) {
    // Daftar izin
    $routes->get('/', 'IzinController::index');

    // Ajukan izin
    $routes->get('create', 'IzinController::create');
    $routes->post('store', 'IzinController::store');

    // Hapus izin
    $routes->get('(:num)/delete', 'IzinController::delete/$1');
});
