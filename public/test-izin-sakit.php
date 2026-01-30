<?php
// public/test-izin-sakit.php - Test script untuk debug

// Simulate CI4 environment
define('ROOTPATH', __DIR__ . '/../');
define('FCPATH', __DIR__ . '/');
define('APPPATH', ROOTPATH . 'app' . DIRECTORY_SEPARATOR);

require ROOTPATH . 'vendor/autoload.php';

// Load database config
$config = new \Config\Database();
$default = $config->default;

// Connect to DB
$mysqli = new mysqli(
    $default['hostname'],
    $default['username'],
    $default['password'],
    $default['database']
);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check tables
echo "===== TABLE CHECK =====\n";
$tables = ['izin_sakit', 'absensi', 'siswa', 'qr_location'];

foreach ($tables as $table) {
    $result = $mysqli->query("SHOW TABLES LIKE '$table'");
    $exists = $result->num_rows > 0 ? "✓ EXISTS" : "✗ NOT FOUND";
    echo "$table: $exists\n";
}

// Check izin_sakit columns
echo "\n===== IZIN_SAKIT COLUMNS =====\n";
$columns = $mysqli->query("DESCRIBE izin_sakit");
if ($columns) {
    while ($col = $columns->fetch_assoc()) {
        echo "  {$col['Field']}: {$col['Type']}\n";
    }
}

// Check absensi columns
echo "\n===== ABSENSI COLUMNS =====\n";
$columns = $mysqli->query("DESCRIBE absensi");
if ($columns) {
    while ($col = $columns->fetch_assoc()) {
        echo "  {$col['Field']}: {$col['Type']}\n";
    }
}

// Check if migrations exist
echo "\n===== MIGRATION FILES =====\n";
$migrationPath = APPPATH . 'Database/Migrations/';
$files = glob($migrationPath . '*.php');
foreach ($files as $file) {
    echo basename($file) . "\n";
}

$mysqli->close();
echo "\n✓ Test completed";
