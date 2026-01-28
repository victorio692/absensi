<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestFullFlow extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'debug:full-flow';
    protected $description = 'Test complete form submission and redirect flow';

    public function run(array $params = [])
    {
        CLI::write('=== TESTING COMPLETE FORM SUBMISSION FLOW ===', 'green');
        
        $baseUrl = 'http://localhost:8080';
        $cookieJar = sys_get_temp_dir() . '/test_flow_cookies.txt';
        
        // Step 1: Login
        CLI::write('\n[STEP 1] Logging in', 'yellow');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_exec($ch);
        curl_close($ch);
        
        $loginData = ['username' => 'admin', 'password' => 'admin123'];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
        curl_exec($ch);
        curl_close($ch);
        
        CLI::write("  ✓ Logged in", 'green');
        
        // Step 2: Count before
        CLI::write('\n[STEP 2] Checking location count BEFORE', 'yellow');
        
        $db = \Config\Database::connect();
        $countBefore = $db->table('qr_location')->countAll();
        CLI::write("  Locations in DB: $countBefore", 'white');
        
        // Step 3: Submit form via CURL with redirect following
        CLI::write('\n[STEP 3] Submitting form with auto-redirect', 'yellow');
        
        $testName = 'Test Full Flow ' . time();
        $postData = [
            'nama_lokasi' => $testName,
            'keterangan' => 'Testing full flow with redirect',
            'aktif' => '1',
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/qr-location/store');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects!
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
        curl_setopt($ch, CURLOPT_HEADER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
        
        $headerSize = strpos($response, "\r\n\r\n");
        $body = substr($response, $headerSize + 4);
        
        CLI::write("  Final HTTP Code: $httpCode", 'white');
        CLI::write("  Final URL: $finalUrl", 'white');
        
        if (strpos($body, 'Lokasi QR berhasil ditambahkan') !== false) {
            CLI::write("  ✓ SUCCESS MESSAGE FOUND in response!", 'green');
        } else {
            CLI::write("  ⚠ Success message NOT found in response", 'yellow');
        }
        
        // Step 4: Count after
        CLI::write('\n[STEP 4] Checking location count AFTER', 'yellow');
        
        $countAfter = $db->table('qr_location')->countAll();
        CLI::write("  Locations in DB: $countAfter", 'white');
        
        if ($countAfter > $countBefore) {
            CLI::write("  ✓ DATA WAS SAVED!", 'green');
        } else {
            CLI::write("  ✗ Data NOT saved", 'red');
        }
        
        // Step 5: Verify data exists
        CLI::write('\n[STEP 5] Verifying data details', 'yellow');
        
        $data = $db->table('qr_location')
            ->where('nama_lokasi', $testName)
            ->get()
            ->getRowArray();
        
        if ($data) {
            CLI::write("  ✓ Data found!", 'green');
            CLI::write("  ID: {$data['id']}", 'white');
            CLI::write("  Nama: {$data['nama_lokasi']}", 'white');
            CLI::write("  Keterangan: {$data['keterangan']}", 'white');
            CLI::write("  Aktif: {$data['aktif']}", 'white');
            CLI::write("  Created: {$data['created_at']}", 'white');
        } else {
            CLI::error("  ✗ Data NOT found in database!");
        }
        
        CLI::write('\n=== TEST COMPLETE ===', 'green');
    }
}
