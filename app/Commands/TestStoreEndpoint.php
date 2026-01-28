<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestStoreEndpoint extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'debug:store-endpoint';
    protected $description = 'Test store endpoint directly';

    public function run(array $params = [])
    {
        CLI::write('=== TESTING STORE ENDPOINT DIRECTLY ===', 'green');
        
        $baseUrl = 'http://localhost:8080';
        $cookieJar = sys_get_temp_dir() . '/test_cookies2.txt';
        
        // Login first
        CLI::write('\n[STEP 1] Login', 'yellow');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
        curl_exec($ch);
        curl_close($ch);
        
        $loginData = ['username' => 'admin', 'password' => 'admin123'];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
        curl_exec($ch);
        curl_close($ch);
        
        CLI::write("  ✓ Logged in", 'green');
        
        // POST data
        CLI::write('\n[STEP 2] POST to /admin/qr-location/store', 'yellow');
        
        $postData = [
            'nama_lokasi' => 'Test ' . time(),
            'keterangan' => 'Test',
            'aktif' => '1',
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/qr-location/store');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $stderr = fopen('php://temp', 'rw+');
        curl_setopt($ch, CURLOPT_STDERR, $stderr);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $info = curl_getinfo($ch);
        curl_close($ch);
        
        $headerSize = $info['header_size'];
        $headers = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        
        // Parse headers
        $headerLines = explode("\r\n", $headers);
        
        CLI::write("  HTTP Code: $httpCode", 'white');
        CLI::write("  Response Headers:", 'yellow');
        foreach ($headerLines as $line) {
            if (!empty($line)) {
                CLI::write("    $line", 'white');
            }
        }
        
        CLI::write("  Response Body (first 500 chars):", 'yellow');
        CLI::write("    " . substr($body, 0, 500), 'white');
        
        if (strpos($body, '<html') !== false || strpos($body, '<!DOCTYPE') !== false) {
            CLI::write("  Body contains HTML", 'yellow');
        }
        if (strpos($body, '<script') !== false) {
            CLI::write("  Body contains JavaScript", 'yellow');
        }
        if (strpos($body, 'function') !== false) {
            CLI::write("  Body contains functions", 'yellow');
        }
        
        CLI::write('\n=== TEST COMPLETE ===', 'green');
    }
}
