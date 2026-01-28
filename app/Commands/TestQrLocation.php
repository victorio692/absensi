<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestQrLocation extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'test:qr-location';
    protected $description = 'Test QR Location data and schema';

    public function run(array $params = [])
    {
        $db = \Config\Database::connect();
        
        // Check table structure
        CLI::write('=== QR_LOCATION TABLE STRUCTURE ===', 'green');
        $result = $db->query("DESCRIBE qr_location");
        foreach ($result->getResultArray() as $row) {
            CLI::write("  {$row['Field']} ({$row['Type']}) - Null: {$row['Null']} - Default: {$row['Default']}");
        }
        
        // Check data
        CLI::write("\n=== QR_LOCATION DATA (Sample) ===", 'green');
        $result = $db->query("SELECT * FROM qr_location LIMIT 5");
        foreach ($result->getResultArray() as $row) {
            CLI::write(json_encode($row, JSON_PRETTY_PRINT));
        }
        
        CLI::write("\n=== TEST INSERT VIA MODEL ===", 'green');
        try {
            $qrLocationModel = model('App\Models\QrLocationModel');
            
            CLI::write("allowedFields: " . json_encode($qrLocationModel->allowedFields));
            
            $result = $qrLocationModel->insert([
                'nama_lokasi' => 'Test via Model ' . time(),
                'keterangan' => 'Test keterangan dari command',
                'aktif' => 1,
            ]);
            
            CLI::write("Insert result: " . var_export($result, true), $result ? 'green' : 'red');
            CLI::write("Insert ID: " . $qrLocationModel->insertID());
            
            // Verify inserted
            $inserted = $qrLocationModel->find($qrLocationModel->insertID());
            CLI::write("Inserted data: " . json_encode($inserted, JSON_PRETTY_PRINT), 'cyan');
            
        } catch (\Exception $e) {
            CLI::error("Error: " . $e->getMessage());
            CLI::error("Trace: " . $e->getTraceAsString());
        }
    }
}
