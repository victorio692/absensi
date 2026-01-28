<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestFormSubmitAuth extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'debug:form-submit-auth';
    protected $description = 'Test form submission via HTTP with authentication';

    public function run(array $params = [])
    {
        CLI::write('=== TESTING FORM SUBMISSION WITH AUTH ===', 'green');
        
        $baseUrl = 'http://localhost:8080';
        $cookieJar = sys_get_temp_dir() . '/test_cookies.txt';
        
        // Step 1: Login first
        CLI::write('\n[STEP 1] Logging in as admin', 'yellow');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
        
        $response = curl_exec($ch);
        curl_close($ch);
        CLI::write("  ✓ Got login page", 'green');
        
        // Try to login with admin credentials
        $loginData = [
            'username' => 'admin',
            'password' => 'admin123',
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        CLI::write("  POST login attempt, HTTP: $httpCode", 'white');
        CLI::write("  ✓ Login completed", 'green');
        
        // Step 2: Get the form page
        CLI::write('\n[STEP 2] Getting form page', 'yellow');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/qr-location/create');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            CLI::error("  ✗ Failed to get form. HTTP Code: $httpCode");
            CLI::write("  This could mean login failed. Check credentials.", 'yellow');
            return;
        }
        
        CLI::write("  ✓ Form page retrieved (HTTP 200)", 'green');
        
        // Step 3: Submit form
        CLI::write('\n[STEP 3] Submitting form data', 'yellow');
        
        $postData = [
            'nama_lokasi' => 'Test Lokasi Auth ' . time(),
            'keterangan' => 'Testing dengan authentication',
            'aktif' => '1',
        ];
        
        CLI::write("  - nama_lokasi: {$postData['nama_lokasi']}", 'white');
        CLI::write("  - keterangan: {$postData['keterangan']}", 'white');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/qr-location/store');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
        curl_setopt($ch, CURLOPT_HEADER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        
        $headers = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        
        curl_close($ch);
        
        CLI::write("  ✓ POST sent", 'green');
        CLI::write("  Response HTTP Code: $httpCode", 'white');
        
        // Show response body
        if ($httpCode === 200) {
            CLI::write("  Response body preview:", 'white');
            $preview = strip_tags($body);
            $preview = str_replace("\n", " ", $preview);
            $preview = preg_replace('/\s+/', ' ', $preview);
            CLI::write("  " . substr($preview, 0, 200), 'white');
        }
        
        // Check for redirect
        if ($httpCode === 302 || $httpCode === 303 || $httpCode === 307) {
            CLI::write("  ✓ Server returned redirect (HTTP $httpCode)", 'green');
            
            preg_match('/Location: (.+)\r?\n/', $headers, $matches);
            $location = trim($matches[1] ?? '');
            CLI::write("  Redirect to: $location", 'white');
            
            if (strpos($location, 'qr-location') !== false) {
                CLI::write("  ✓ Redirecting to qr-location list (SUCCESS expected)", 'green');
            } else if (strpos($location, 'login') !== false) {
                CLI::error("  ✗ Redirecting to login (NOT authenticated!)", 'red');
            }
        }
        
        // Step 4: Verify data
        CLI::write('\n[STEP 4] Checking if data was saved', 'yellow');
        
        $db = \Config\Database::connect();
        $nama = $postData['nama_lokasi'];
        
        $result = $db->table('qr_location')
            ->where('nama_lokasi', $nama)
            ->get()
            ->getRowArray();
        
        if ($result) {
            CLI::write("  ✓ DATA FOUND IN DATABASE!", 'green');
            CLI::write("  ID: {$result['id']}", 'green');
            CLI::write("  Nama: {$result['nama_lokasi']}", 'green');
        } else {
            CLI::error("  ✗ Data NOT found in database");
        }
        
        CLI::write('\n=== FORM SUBMISSION TEST COMPLETE ===', 'green');
    }
}
