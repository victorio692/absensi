<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestStoreDirectly extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'debug:store-direct';
    protected $description = 'Call store() method directly to test it';

    public function run(array $params = [])
    {
        CLI::write('=== TESTING STORE() METHOD DIRECTLY ===', 'green');
        
        // Simulate request data
        CLI::write('\n[STEP 1] Simulating form submission', 'yellow');
        
        $_REQUEST = [
            'nama_lokasi' => 'Test Direct Store ' . time(),
            'keterangan' => 'Testing store() directly',
            'aktif' => '1',
        ];
        
        $_POST = $_REQUEST;
        
        CLI::write("  nama_lokasi: {$_POST['nama_lokasi']}", 'white');
        CLI::write("  keterangan: {$_POST['keterangan']}", 'white');
        CLI::write("  aktif: {$_POST['aktif']}", 'white');
        
        // Create mock request
        CLI::write('\n[STEP 2] Creating mock request', 'yellow');
        
        $request = service('request');
        
        // Use IncomingRequest methods to set POST data
        $request->setGlobal('post', $_POST);
        
        CLI::write("  Request method: " . $request->getMethod(), 'white');
        
        // Count before
        $db = \Config\Database::connect();
        $countBefore = $db->table('qr_location')->countAll();
        CLI::write("\n[STEP 3] Location count BEFORE: $countBefore", 'yellow');
        
        // Call store method
        CLI::write('\n[STEP 4] Calling QrLocation::store()', 'yellow');
        
        try {
            $controller = new \App\Controllers\QrLocation();
            $result = $controller->store();
            
            CLI::write("  ✓ store() executed", 'green');
            CLI::write("  Result type: " . get_class($result), 'white');
        } catch (\Exception $e) {
            CLI::error("  ✗ EXCEPTION in store(): " . $e->getMessage());
            CLI::write("  Trace: " . $e->getTraceAsString(), 'red');
        }
        
        // Count after
        CLI::write('\n[STEP 5] Location count AFTER: ', 'yellow');
        $countAfter = $db->table('qr_location')->countAll();
        CLI::write("  $countAfter", 'white');
        
        if ($countAfter > $countBefore) {
            CLI::write("  ✓ DATA SAVED!", 'green');
        } else {
            CLI::write("  ✗ Data NOT saved", 'red');
        }
        
        CLI::write('\n=== TEST COMPLETE ===', 'green');
    }
}
