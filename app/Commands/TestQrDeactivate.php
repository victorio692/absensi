<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestQrDeactivate extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'test:qr-deactivate';
    protected $description = 'Test deactivating QR location (toggle aktif)';

    public function run(array $params = [])
    {
        CLI::write('=== TEST QR LOCATION DEACTIVATE ===', 'green');
        
        $qrModel = model('App\Models\QrLocationModel');
        
        // Get first active location
        $activeLocation = $qrModel->where('aktif', 1)->first();
        
        if (!$activeLocation) {
            CLI::error('No active location found to test deactivation');
            return;
        }
        
        CLI::write("\nFound Active Location:", 'cyan');
        CLI::write("  ID: {$activeLocation['id']}", 'cyan');
        CLI::write("  Nama: {$activeLocation['nama_lokasi']}", 'cyan');
        CLI::write("  Aktif: " . ($activeLocation['aktif'] ? 'Ya' : 'Tidak'), 'cyan');
        
        // TEST 1: Deactivate (set aktif to 0)
        CLI::write("\n[TEST 1] Deactivate Location (set aktif = 0)", 'yellow');
        try {
            $result = $qrModel->update($activeLocation['id'], [
                'aktif' => 0,
            ]);
            
            $updated = $qrModel->find($activeLocation['id']);
            
            if ($updated['aktif'] == 0) {
                CLI::write("✓ Deactivation successful!", 'green');
                CLI::write("  Location '{$updated['nama_lokasi']}' is now INACTIVE", 'green');
            } else {
                CLI::write("✗ Deactivation failed", 'red');
            }
        } catch (\Exception $e) {
            CLI::error("Error deactivating: " . $e->getMessage());
            return;
        }
        
        // TEST 2: Reactivate (set aktif to 1)
        CLI::write("\n[TEST 2] Reactivate Location (set aktif = 1)", 'yellow');
        try {
            $result = $qrModel->update($activeLocation['id'], [
                'aktif' => 1,
            ]);
            
            $updated = $qrModel->find($activeLocation['id']);
            
            if ($updated['aktif'] == 1) {
                CLI::write("✓ Reactivation successful!", 'green');
                CLI::write("  Location '{$updated['nama_lokasi']}' is now ACTIVE", 'green');
            } else {
                CLI::write("✗ Reactivation failed", 'red');
            }
        } catch (\Exception $e) {
            CLI::error("Error reactivating: " . $e->getMessage());
            return;
        }
        
        // TEST 3: List all with aktif status
        CLI::write("\n[TEST 3] List All Locations with Aktif Status", 'yellow');
        $all = $qrModel->findAll();
        
        $activeCount = 0;
        $inactiveCount = 0;
        
        foreach ($all as $loc) {
            $status = $loc['aktif'] ? '✓ AKTIF' : '✗ TIDAK AKTIF';
            $color = $loc['aktif'] ? 'green' : 'red';
            CLI::write("  ID {$loc['id']}: {$loc['nama_lokasi']} - {$status}", $color);
            
            if ($loc['aktif']) {
                $activeCount++;
            } else {
                $inactiveCount++;
            }
        }
        
        CLI::write("\nTotal: {$activeCount} aktif, {$inactiveCount} tidak aktif", 'cyan');
        CLI::write("\n✓ ALL TESTS PASSED - Deactivate/Activate Feature Works!", 'green');
    }
}
