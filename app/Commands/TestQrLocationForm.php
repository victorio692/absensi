<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestQrLocationForm extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'test:qr-form';
    protected $description = 'Test QR Location form submission';

    public function run(array $params = [])
    {
        CLI::write('=== QR LOCATION FORM TEST ===', 'green');
        CLI::write('Testing form submission with curl...');
        
        // Simulate form submission
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/admin/qr-location/store');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'nama_lokasi' => 'Test Lokasi via CURL',
            'keterangan' => 'Testing with curl',
            'aktif' => 1,
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        CLI::write("HTTP Code: " . $httpCode, $httpCode == 302 ? 'green' : 'red');
        
        if ($httpCode == 302) {
            CLI::write("✓ Form submission successful (redirected)", 'green');
            
            // Check if inserted
            $db = \Config\Database::connect();
            $result = $db->table('qr_location')
                ->where('nama_lokasi', 'Test Lokasi via CURL')
                ->first();
                
            if ($result) {
                CLI::write("✓ Data inserted successfully", 'green');
                CLI::write(json_encode($result, JSON_PRETTY_PRINT));
            } else {
                CLI::write("✗ Data not inserted", 'red');
            }
        } else {
            CLI::write("✗ Form submission failed or response not as expected", 'red');
            CLI::write("Response headers:", 'yellow');
            $lines = explode("\n", substr($response, 0, 500));
            foreach ($lines as $line) {
                if (trim($line)) CLI::write($line);
            }
        }
    }
}
