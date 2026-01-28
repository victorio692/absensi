<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestSessionFlashdata extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'debug:session-flashdata';
    protected $description = 'Test if flashdata works correctly';

    public function run(array $params = [])
    {
        CLI::write('=== TESTING SESSION FLASHDATA ===', 'green');
        
        // Initialize session
        $session = service('session');
        
        CLI::write('\n[STEP 1] Getting session info', 'yellow');
        CLI::write("  Session class: " . get_class($session), 'white');
        CLI::write("  Session driver config: " . get_class(config('Session')), 'white');
        
        // Check flashdata initially
        CLI::write('\n[STEP 2] Before setting flashdata', 'yellow');
        $before = $session->get('success');
        CLI::write("  session('success'): " . var_export($before, true), 'white');
        
        // Set flashdata
        CLI::write('\n[STEP 3] Setting flashdata', 'yellow');
        $session->setFlashdata('success', 'Lokasi QR berhasil ditambahkan!');
        CLI::write("  ✓ setFlashdata() called", 'green');
        
        // Check immediately after
        CLI::write('\n[STEP 4] Immediately after setting', 'yellow');
        $after = $session->get('success');
        CLI::write("  session('success'): " . var_export($after, true), 'white');
        
        if ($after === 'Lokasi QR berhasil ditambahkan!') {
            CLI::write("  ✓ Flashdata accessible immediately", 'green');
        } else {
            CLI::write("  ✗ Flashdata NOT accessible", 'red');
        }
        
        // Get all session data
        CLI::write('\n[STEP 5] All session data', 'yellow');
        $allData = $session->getFlashdata();
        CLI::write("  Flashdata keys: " . json_encode(array_keys($allData)), 'white');
        
        // Check session files
        CLI::write('\n[STEP 6] Checking session storage', 'yellow');
        $sessionPath = WRITEPATH . 'session';
        CLI::write("  Session path: $sessionPath", 'white');
        
        if (is_dir($sessionPath)) {
            $files = array_diff(scandir($sessionPath), ['.', '..']);
            CLI::write("  Session files found: " . count($files), 'white');
            foreach (array_slice($files, 0, 3) as $file) {
                CLI::write("    - $file", 'white');
            }
        } else {
            CLI::write("  ✗ Session directory does not exist!", 'red');
        }
        
        // Test persist to session file
        CLI::write('\n[STEP 7] Testing session save', 'yellow');
        try {
            $session->markFlashdata();
            CLI::write("  ✓ markFlashdata() successful", 'green');
        } catch (\Exception $e) {
            CLI::error("  ✗ EXCEPTION: " . $e->getMessage());
        }
        
        CLI::write('\n=== TEST COMPLETE ===', 'green');
    }
}
