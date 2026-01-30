<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Helpers\AlphaAutomaticHelper;

class AlphaGenerate extends BaseCommand
{
    protected $group = 'Attendance';
    protected $name = 'alpha:generate';
    protected $description = 'Generate alpha otomatis untuk siswa yang tidak absen';
    protected $usage = 'alpha:generate [tanggal]';
    protected $arguments = [
        'tanggal' => 'Tanggal untuk generate alpha (format: Y-m-d). Default: hari ini',
    ];

    public function run(array $params = [])
    {
        $tanggal = $params[0] ?? date('Y-m-d');

        // Validasi format tanggal
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            CLI::error('Format tanggal tidak valid. Gunakan format Y-m-d (contoh: 2026-01-30)');
            return;
        }

        // Validasi tanggal tidak boleh di masa depan
        if (strtotime($tanggal) > strtotime(date('Y-m-d'))) {
            CLI::error('Tidak bisa generate alpha untuk tanggal di masa depan');
            return;
        }

        CLI::write('Generating alpha otomatis untuk tanggal: ' . $tanggal, 'cyan');

        // Cek apakah sudah pernah di-generate
        if (AlphaAutomaticHelper::isAlphaAlreadyGenerated($tanggal)) {
            CLI::write('Peringatan: Alpha untuk tanggal ini sudah pernah di-generate sebelumnya', 'yellow');
        }

        // Generate alpha
        $result = AlphaAutomaticHelper::generateAlpha($tanggal);

        if ($result['success']) {
            CLI::write('✓ Berhasil generate ' . $result['generated'] . ' alpha record', 'green');
            CLI::write('Message: ' . $result['message'], 'cyan');
        } else {
            CLI::error('✗ Gagal generate alpha: ' . $result['message']);
        }
    }
}
