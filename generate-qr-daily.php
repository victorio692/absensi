<?php
// Script untuk generate QR codes harian

// Set environment
define('ENVIRONMENT', 'development');
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Bootstrap CodeIgniter
require ROOTPATH . 'vendor/autoload.php';

use CodeIgniter\Services;
use App\Models\QrLocationModel;
use App\Models\QrDailyModel;

// Load Config
$config = require ROOTPATH . 'app/Config/Paths.php';
define('FCPATH', $config->basePath . DIRECTORY_SEPARATOR);
define('APPPATH', $config->appPath . DIRECTORY_SEPARATOR);
define('PUBPATH', $config->publicPath . DIRECTORY_SEPARATOR);
define('WRITABLE_PATH', $config->writablePath . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', $config->systemPath . DIRECTORY_SEPARATOR);

$app = require ROOTPATH . 'vendor/codeigniter4/framework/system/bootstrap.php';

try {
    // Generate QR codes
    $qrLocationModel = new QrLocationModel();
    $generated = $qrLocationModel->generateDailyQrCodes();

    echo "✓ QR Code berhasil di-generate untuk " . count($generated) . " lokasi\n";
    echo "Tanggal: " . date('Y-m-d H:i:s') . "\n";
    
    foreach ($generated as $locationId) {
        echo "  - Location ID: " . $locationId . "\n";
    }

} catch (\Throwable $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
