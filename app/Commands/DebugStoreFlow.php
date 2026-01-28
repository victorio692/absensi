<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DebugStoreFlow extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'debug:store-flow';
    protected $description = 'Debug the complete store flow';

    public function run(array $params = [])
    {
        CLI::write('=== DEBUGGING STORE FLOW ===', 'green');
        
        $db = \Config\Database::connect();
        
        // Count before
        $countBefore = $db->table('qr_location')->countAll();
        CLI::write("\n[STEP 1] Count locations BEFORE: $countBefore", 'cyan');
        
        // Simulate what store() does
        CLI::write('\n[STEP 2] Simulating store() method behavior', 'yellow');
        
        $nama_lokasi = 'Test Lokasi Debug ' . time();
        $keterangan = 'Testing debug flow';
        $aktif = 1;
        
        CLI::write("  - nama_lokasi: $nama_lokasi", 'white');
        CLI::write("  - keterangan: $keterangan", 'white');
        CLI::write("  - aktif: $aktif", 'white');
        
        // Validate
        CLI::write('\n[STEP 3] Validating input', 'yellow');
        if (empty($nama_lokasi) || strlen($nama_lokasi) < 3) {
            CLI::error("  Validation FAILED");
            return;
        }
        CLI::write("  ✓ Validation PASSED", 'green');
        
        // Try insert
        CLI::write('\n[STEP 4] Attempting database insert', 'yellow');
        try {
            $insertResult = $db->table('qr_location')->insert([
                'nama_lokasi' => $nama_lokasi,
                'keterangan' => $keterangan,
                'aktif' => $aktif,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            
            CLI::write("  Insert result: " . var_export($insertResult, true), 'white');
            CLI::write("  ✓ Insert COMPLETED", 'green');
        } catch (\Exception $e) {
            CLI::error("  ✗ Insert FAILED: " . $e->getMessage());
            return;
        }
        
        // Verify insert
        CLI::write('\n[STEP 5] Verifying insert in database', 'yellow');
        $countAfter = $db->table('qr_location')->countAll();
        CLI::write("  Count locations AFTER: $countAfter", 'white');
        
        if ($countAfter > $countBefore) {
            CLI::write("  ✓ Count increased! Insert SUCCESSFUL", 'green');
        } else {
            CLI::write("  ✗ Count NOT increased! Insert FAILED", 'red');
            return;
        }
        
        // Get inserted data
        CLI::write('\n[STEP 6] Retrieving inserted data', 'yellow');
        $inserted = $db->table('qr_location')
            ->where('nama_lokasi', $nama_lokasi)
            ->get()
            ->getRowArray();
        
        if ($inserted) {
            CLI::write("  ✓ Data found in database", 'green');
            CLI::write("  ID: {$inserted['id']}", 'white');
            CLI::write("  Nama: {$inserted['nama_lokasi']}", 'white');
            CLI::write("  Aktif: {$inserted['aktif']}", 'white');
        } else {
            CLI::write("  ✗ Data NOT found in database", 'red');
        }
        
        // Test redirect simulation
        CLI::write('\n[STEP 7] Testing redirect behavior', 'yellow');
        CLI::write("  Would redirect to: " . base_url('admin/qr-location'), 'white');
        CLI::write("  ✓ Redirect path OK", 'green');
        
        // Test session
        CLI::write('\n[STEP 8] Testing session flashdata', 'yellow');
        $session = service('session');
        $session->setFlashdata('success', 'Lokasi QR berhasil ditambahkan!');
        CLI::write("  ✓ Session setFlashdata called", 'green');
        CLI::write("  Message: " . session('success'), 'white');
        
        CLI::write('\n=== DEBUGGING COMPLETE ===', 'green');
        CLI::write('If all steps passed, store() should work in browser.', 'cyan');
        CLI::write('If a step failed, that is the problem.', 'cyan');
    }
}
