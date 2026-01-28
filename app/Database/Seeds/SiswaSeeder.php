<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        $siswaData = [];

        // Data siswa
        $data = [
            ['nama' => 'Ahmad Rafli', 'username' => 'ahmad.rafli', 'nis' => '2024001', 'kelas' => '12 RPL A'],
            ['nama' => 'Budi Santoso', 'username' => 'budi.santoso', 'nis' => '2024002', 'kelas' => '12 RPL A'],
            ['nama' => 'Citra Dewi', 'username' => 'citra.dewi', 'nis' => '2024003', 'kelas' => '12 RPL A'],
            ['nama' => 'Dina Maharani', 'username' => 'dina.maharani', 'nis' => '2024004', 'kelas' => '12 RPL B'],
            ['nama' => 'Eka Putra', 'username' => 'eka.putra', 'nis' => '2024005', 'kelas' => '12 RPL B'],
            ['nama' => 'Fathir Rahman', 'username' => 'fathir.rahman', 'nis' => '2024006', 'kelas' => '12 RPL B'],
        ];

        // Insert ke users dan siswa
        foreach ($data as $siswa) {
            // Insert ke users
            $userId = $this->db->table('users')->insert([
                'nama'     => $siswa['nama'],
                'username' => $siswa['username'],
                'password' => password_hash('siswa123', PASSWORD_BCRYPT),
                'role'     => 'siswa',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // Insert ke siswa (tanpa qr_token)
            $lastId = $this->db->insertID();
            $this->db->table('siswa')->insert([
                'user_id'   => $lastId,
                'nisn'      => null, // nisn bisa diisi nanti atau saat registrasi
                'nis'       => $siswa['nis'],
                'kelas'     => $siswa['kelas'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
