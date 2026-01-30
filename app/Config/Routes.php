<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ===============================================
// API Routes (JSON Response)
// ===============================================

// Auth API
$routes->post('/api/auth/login-siswa', 'Api\AuthController::loginSiswa');
$routes->post('/api/auth/login-admin', 'Api\AuthController::loginAdmin');
$routes->get('/api/auth/logout', 'Api\AuthController::logout');

// Student API (Protected - requires session)
$routes->get('/api/student/dashboard', 'Api\StudentController::dashboard');
$routes->post('/api/student/checkin', 'Api\StudentController::checkin');
$routes->post('/api/student/checkout', 'Api\StudentController::checkout');

// Admin API (Protected - requires session)
$routes->get('/api/admin/dashboard', 'Api\AdminController::dashboard');
$routes->get('/api/admin/attendance', 'Api\AdminController::attendance');
$routes->get('/api/admin/classes', 'Api\AdminController::classes');

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
    $routes->post('qr-location/update', 'QrLocation::update');
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

    // Monitor QR (NEW - Untuk display di monitor)
    $routes->get('monitor/select', 'MonitorQr::selectLocation');
    $routes->get('monitor/display/(:num)', 'MonitorQr::display/$1');
    $routes->get('api/monitor/qr/(:num)', 'MonitorQr::getQrData/$1');

    // Absensi (Admin)
    $routes->get('absensi', 'AbsensiController::index');
    $routes->get('absensi/export-pdf', 'AbsensiController::exportPDF');

    // Pengaturan Jam Sekolah
    $routes->get('pengaturan', 'PengaturanController::index');
    $routes->post('pengaturan/update', 'PengaturanController::update');

    // Manajemen Izin & Sakit (NEW)
    $routes->get('izin-sakit', 'AdminIzinSakit::index');
    $routes->get('izin-sakit-detail/(:num)', 'AdminIzinSakit::detail/$1');
    $routes->post('izin-sakit-approve/(:num)', 'AdminIzinSakit::approve/$1');
    $routes->post('izin-sakit-reject/(:num)', 'AdminIzinSakit::reject/$1');
    $routes->post('izin-sakit-delete/(:num)', 'AdminIzinSakit::delete/$1');
    $routes->get('izin-sakit-download-bukti/(:num)', 'AdminIzinSakit::downloadBukti/$1');

    // Verifikasi Izin/Sakit (Legacy)
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
// Siswa Izin/Sakit Routes (Dengan Filter Auth)
// ===============================================
$routes->group('siswa', ['filter' => 'auth:siswa'], static function ($routes) {
    // Izin/Sakit (NEW)
    $routes->get('izin-sakit-create', 'IzinSakit::create');
    $routes->post('izin-sakit-store', 'IzinSakit::store');
    $routes->get('izin-sakit-riwayat', 'IzinSakit::riwayat');
    $routes->get('izin-sakit-detail/(:num)', 'IzinSakit::detail/$1');
    $routes->get('izin-sakit-download-bukti/(:num)', 'IzinSakit::downloadBukti/$1');
});
