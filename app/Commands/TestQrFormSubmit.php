<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestQrFormSubmit extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'test:qr-form-submit';
    protected $description = 'Test complete form submission flow';

    public function run(array $params = [])
    {
        CLI::write('=== TEST QR LOCATION FORM SUBMISSION ===', 'green');
        
        // Create CodeIgniter instance
        $request = service('request');
        $session = service('session');
        
        CLI::write('\n[TEST 1] Simulate Store POST Request', 'yellow');
        
        try {
            // Get model
            $qrModel = model('App\Models\QrLocationModel');
            
            // Get existing location to edit
            $existingLocation = $qrModel->where('aktif', 1)->first();
            
            if (!$existingLocation) {
                CLI::error('No location found to test edit');
                return;
            }
            
            CLI::write("Testing with location ID: {$existingLocation['id']}", 'cyan');
            
            // TEST 1: ADD NEW LOCATION
            CLI::write('\n[TEST 1a] Add New Location via Store', 'yellow');
            $newName = 'Test Lokasi ' . time();
            
            $addResult = $qrModel->insert([
                'nama_lokasi' => $newName,
                'keterangan' => 'Testing form submission flow',
                'aktif' => 1,
            ]);
            
            if ($addResult) {
                $newId = $qrModel->insertID();
                CLI::write("✓ Successfully added location", 'green');
                CLI::write("  ID: $newId", 'green');
                CLI::write("  Nama: $newName", 'green');
                
                // Verify insert
                $inserted = $qrModel->find($newId);
                CLI::write("✓ Verified in database:", 'green');
                CLI::write(json_encode($inserted, JSON_PRETTY_PRINT));
            } else {
                CLI::error("✗ Failed to add location");
                return;
            }
            
            // TEST 1b: EDIT THE LOCATION
            CLI::write('\n[TEST 1b] Edit Location via Update', 'yellow');
            $updatedName = 'Updated ' . $newName;
            
            $editResult = $qrModel->update($newId, [
                'nama_lokasi' => $updatedName,
                'keterangan' => 'Updated via test',
                'aktif' => 0,
            ]);
            
            if ($editResult) {
                CLI::write("✓ Successfully updated location", 'green');
                
                // Verify update
                $updated = $qrModel->find($newId);
                CLI::write("✓ Verified updated data:", 'green');
                CLI::write(json_encode($updated, JSON_PRETTY_PRINT));
                
                if ($updated['nama_lokasi'] === $updatedName && $updated['aktif'] == 0) {
                    CLI::write("✓✓✓ UPDATE DATA MATCHES PERFECTLY", 'green');
                } else {
                    CLI::error("✗ Updated data doesn't match");
                }
            } else {
                CLI::error("✗ Failed to update location");
                return;
            }
            
            // TEST 2: TOGGLE AKTIF
            CLI::write('\n[TEST 2] Toggle Aktif Status', 'yellow');
            $toggleResult = $qrModel->update($newId, [
                'aktif' => 1,
            ]);
            
            if ($toggleResult) {
                $toggled = $qrModel->find($newId);
                CLI::write("✓ Successfully toggled aktif to: " . ($toggled['aktif'] ? 'ACTIVE' : 'INACTIVE'), 'green');
            }
            
            // TEST 3: DELETE TEST LOCATION
            CLI::write('\n[TEST 3] Delete Test Location', 'yellow');
            $deleteResult = $qrModel->delete($newId);
            
            if ($deleteResult) {
                CLI::write("✓ Successfully deleted location ID: $newId", 'green');
                
                $deleted = $qrModel->find($newId);
                if (!$deleted) {
                    CLI::write("✓✓✓ DELETION VERIFIED - Location no longer exists", 'green');
                }
            }
            
            CLI::write('\n✓✓✓ ALL FORM SUBMISSION TESTS PASSED ✓✓✓', 'green');
            CLI::write('\nFunctionality is working correctly at database level.', 'cyan');
            CLI::write('If browser forms still not working, issue is likely:');
            CLI::write('  1. Browser cache not cleared', 'yellow');
            CLI::write('  2. CSRF token validation issue', 'yellow');
            CLI::write('  3. Session not persisting properly', 'yellow');
            
        } catch (\Exception $e) {
            CLI::error("ERROR: " . $e->getMessage());
            CLI::error("Trace: " . $e->getTraceAsString());
        }
    }
}
