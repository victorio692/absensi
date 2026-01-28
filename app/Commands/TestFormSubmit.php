<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestFormSubmit extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'debug:form-submit';
    protected $description = 'Test form submission via HTTP like browser does';

    public function run(array $params = [])
    {
        CLI::write('=== TESTING FORM SUBMISSION VIA HTTP ===', 'green');
        
        $baseUrl = 'http://localhost:8080';
        
        // First, get the form page to extract CSRF token
        CLI::write('\n[STEP 1] Getting form page to extract CSRF token', 'yellow');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/qr-location/create');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, sys_get_temp_dir() . '/cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, sys_get_temp_dir() . '/cookies.txt');
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            CLI::error("  ✗ Failed to get form page. HTTP Code: $httpCode");
            return;
        }
        
        CLI::write("  ✓ Form page retrieved (HTTP $httpCode)", 'green');
        
        // Extract CSRF token - look for any input with name containing hash
        preg_match('/<input[^>]*name="([a-f0-9]+)"[^>]*value="([^"]*)"[^>]*type="hidden"/', $response, $matches);
        $csrfToken = $matches[2] ?? null;
        
        if (!$csrfToken) {
            CLI::write("  ⚠ CSRF token not found in form (may not be needed)", 'yellow');
        } else {
            CLI::write("  ✓ CSRF token extracted: " . substr($csrfToken, 0, 10) . '...', 'green');
        }
        
        // Now POST form data
        CLI::write('\n[STEP 2] Submitting form via POST', 'yellow');
        
        $postData = [
            'nama_lokasi' => 'Test Lokasi Dari Form ' . time(),
            'keterangan' => 'Testing form submission dengan HTTP',
            'aktif' => '1',
        ];
        
        if ($csrfToken) {
            $postData['csrf_token'] = $csrfToken;
        }
        
        CLI::write("  - nama_lokasi: {$postData['nama_lokasi']}", 'white');
        CLI::write("  - keterangan: {$postData['keterangan']}", 'white');
        CLI::write("  - aktif: {$postData['aktif']}", 'white');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/qr-location/store');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Don't follow to see redirect
        curl_setopt($ch, CURLOPT_COOKIEJAR, sys_get_temp_dir() . '/cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, sys_get_temp_dir() . '/cookies.txt');
        curl_setopt($ch, CURLOPT_HEADER, true); // Get headers
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        
        $headers = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        
        curl_close($ch);
        
        CLI::write("  ✓ POST request sent to /admin/qr-location/store", 'green');
        CLI::write("  Response HTTP Code: $httpCode", 'white');
        
        // Check for redirect
        if ($httpCode === 302 || $httpCode === 303 || $httpCode === 307) {
            CLI::write("  ✓ Server returned redirect (HTTP $httpCode)", 'green');
            
            // Extract Location header
            preg_match('/Location: (.+)\r?\n/', $headers, $matches);
            $location = trim($matches[1] ?? '');
            CLI::write("  Redirect to: $location", 'white');
        } elseif ($httpCode === 200) {
            CLI::write("  ⚠ No redirect, got 200 OK", 'yellow');
            CLI::write("  Body preview: " . substr(strip_tags($body), 0, 100), 'white');
        } else {
            CLI::error("  Unexpected HTTP code: $httpCode");
        }
        
        // Now check if data was saved
        CLI::write('\n[STEP 3] Verifying data was saved to database', 'yellow');
        
        $db = \Config\Database::connect();
        $nama = $postData['nama_lokasi'];
        
        $result = $db->table('qr_location')
            ->where('nama_lokasi', $nama)
            ->get()
            ->getRowArray();
        
        if ($result) {
            CLI::write("  ✓ Data found in database!", 'green');
            CLI::write("  ID: {$result['id']}", 'white');
            CLI::write("  Nama: {$result['nama_lokasi']}", 'white');
            CLI::write("  Aktif: {$result['aktif']}", 'white');
        } else {
            CLI::error("  ✗ Data NOT found in database!");
        }
        
        // Check cookies for session
        CLI::write('\n[STEP 4] Checking cookies', 'yellow');
        
        $cookieFile = sys_get_temp_dir() . '/cookies.txt';
        if (file_exists($cookieFile)) {
            $cookies = file_get_contents($cookieFile);
            if (strpos($cookies, 'absensi_qr_session') !== false) {
                CLI::write("  ✓ Session cookie found", 'green');
            } else {
                CLI::write("  ⚠ Session cookie not found", 'yellow');
            }
        }
        
        CLI::write('\n=== TEST COMPLETE ===', 'green');
    }
}
