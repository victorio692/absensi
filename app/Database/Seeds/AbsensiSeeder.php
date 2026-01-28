<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    public function run()
    {
        // Data riwayat absensi untuk siswa pertama (Ahmad Rafli - siswa_id 1)
        $data = [
            [
                'siswa_id'   => 1,
                'tanggal'    => date('Y-m-d', strtotime('-4 days')),
                'jam_masuk'  => '07:15:00',
                'jam_pulang' => '15:30:00',
                'status'     => 'Hadir',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
            [
                'siswa_id'   => 1,
                'tanggal'    => date('Y-m-d', strtotime('-3 days')),
                'jam_masuk'  => '07:45:00',
                'jam_pulang' => '15:45:00',
                'status'     => 'Hadir',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'siswa_id'   => 1,
                'tanggal'    => date('Y-m-d', strtotime('-2 days')),
                'jam_masuk'  => '07:05:00',
                'jam_pulang' => '15:20:00',
                'status'     => 'Hadir',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'siswa_id'   => 1,
                'tanggal'    => date('Y-m-d', strtotime('-1 days')),
                'jam_masuk'  => '08:00:00',
                'jam_pulang' => null,
                'status'     => 'Hadir',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            ],
            [
                'siswa_id'   => 1,
                'tanggal'    => date('Y-m-d'),
                'jam_masuk'  => '07:30:00',
                'jam_pulang' => '15:35:00',
                'status'     => 'Hadir',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert data
        $this->db->table('absensi')->insertBatch($data);
    }
}
