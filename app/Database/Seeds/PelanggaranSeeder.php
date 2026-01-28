<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PelanggaranSeeder extends Seeder
{
    public function run()
    {
        $bulanIni = date('Y-m-01');

        $data = [
            // Siswa 1: 2 terlambat, 1 alpha
            [
                'siswa_id' => 1,
                'bulan'    => $bulanIni,
                'jenis'    => 'Terlambat',
                'jumlah'   => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'siswa_id' => 1,
                'bulan'    => $bulanIni,
                'jenis'    => 'Alpha',
                'jumlah'   => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Siswa 2: 1 terlambat
            [
                'siswa_id' => 2,
                'bulan'    => $bulanIni,
                'jenis'    => 'Terlambat',
                'jumlah'   => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Siswa 3: 3 terlambat, 2 alpha
            [
                'siswa_id' => 3,
                'bulan'    => $bulanIni,
                'jenis'    => 'Terlambat',
                'jumlah'   => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'siswa_id' => 3,
                'bulan'    => $bulanIni,
                'jenis'    => 'Alpha',
                'jumlah'   => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('pelanggaran')->insertBatch($data);
    }
}
