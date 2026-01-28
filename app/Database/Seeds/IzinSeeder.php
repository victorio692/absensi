<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class IzinSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Izin disetujui untuk siswa 1
            [
                'siswa_id'   => 1,
                'tanggal'    => date('Y-m-d', strtotime('-1 days')),
                'jenis'      => 'izin',
                'keterangan' => 'Izin untuk urusan keluarga yang penting',
                'file_bukti' => null,
                'status'     => 'Disetujui',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            // Izin menunggu untuk siswa 1
            [
                'siswa_id'   => 1,
                'tanggal'    => date('Y-m-d'),
                'jenis'      => 'sakit',
                'keterangan' => 'Sakit demam tinggi, perlu istirahat',
                'file_bukti' => null,
                'status'     => 'Menunggu',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Izin ditolak untuk siswa 2
            [
                'siswa_id'   => 2,
                'tanggal'    => date('Y-m-d', strtotime('-3 days')),
                'jenis'      => 'izin',
                'keterangan' => 'Izin untuk acara pribadi',
                'file_bukti' => null,
                'status'     => 'Ditolak',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
        ];

        $this->db->table('izin')->insertBatch($data);
    }
}
