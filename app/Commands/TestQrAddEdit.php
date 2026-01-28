<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestQrAddEdit extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'test:qr-add-edit';
    protected $description = 'Test adding and editing QR location';

    public function run(array $params = [])
    {
        CLI::write('=== TEST QR LOCATION ADD & EDIT ===', 'green');
        
        $db = \Config\Database::connect();
        $qrModel = model('App\Models\QrLocationModel');
        
        // TEST 1: Direct Model Insert
        CLI::write('\n[TEST 1] Direct Model Insert', 'yellow');
        try {
            $testName = 'Test Add Location ' . time();
            $result = $qrModel->insert([
                'nama_lokasi' => $testName,
                'keterangan' => 'Testing add functionality',
                'aktif' => 1,
            ]);
            
            $insertedId = $qrModel->insertID();
            CLI::write("✓ Insert successful - ID: $insertedId", 'green');
            
            // Verify
            $data = $qrModel->find($insertedId);
            CLI::write("✓ Data verified in database", 'green');
            CLI::write(json_encode($data, JSON_PRETTY_PRINT));
            
            // TEST 2: Update
            CLI::write('\n[TEST 2] Direct Model Update', 'yellow');
            $updateResult = $qrModel->update($insertedId, [
                'nama_lokasi' => 'Updated ' . $testName,
                'keterangan' => 'Updated description',
                'aktif' => 0,
            ]);
            
            CLI::write("✓ Update successful", 'green');
            
            // Verify update
            $updated = $qrModel->find($insertedId);
            CLI::write("✓ Updated data:", 'green');
            CLI::write(json_encode($updated, JSON_PRETTY_PRINT));
            
            // TEST 3: Via Database Query
            CLI::write('\n[TEST 3] Via Database Query', 'yellow');
            $testName2 = 'Test via DB Query ' . time();
            $dbInsert = $db->table('qr_location')->insert([
                'nama_lokasi' => $testName2,
                'keterangan' => 'Test via DB query',
                'aktif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            
            if ($dbInsert) {
                CLI::write("✓ DB Insert successful - ID: " . $db->insertID(), 'green');
            } else {
                CLI::error("✗ DB Insert failed");
            }
            
            // TEST 4: List all locations
            CLI::write('\n[TEST 4] List All Locations', 'yellow');
            $all = $qrModel->findAll();
            CLI::write("Total locations in database: " . count($all), 'cyan');
            foreach ($all as $loc) {
                CLI::write("  - ID: {$loc['id']}, Nama: {$loc['nama_lokasi']}, Aktif: " . ($loc['aktif'] ? 'Ya' : 'Tidak'));
            }
            
            CLI::write('\n✓✓✓ ALL TESTS PASSED ✓✓✓', 'green');
            
        } catch (\Exception $e) {
            CLI::error("ERROR: " . $e->getMessage());
            CLI::error("Trace: " . $e->getTraceAsString());
        }
    }
}
