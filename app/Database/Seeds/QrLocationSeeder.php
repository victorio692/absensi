<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class QrLocationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_lokasi' => 'Gerbang Masuk',
                'aktif'       => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama_lokasi' => 'Aula',
                'aktif'       => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama_lokasi' => 'Lapangan',
                'aktif'       => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama_lokasi' => 'Ruang Kelas Utama',
                'aktif'       => 0,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('qr_location')->insertBatch($data);
    }
}
