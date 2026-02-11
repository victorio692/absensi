<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataAwalSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        $this->db->disableForeignKeyChecks();

        // Hapus data lama
        $this->db->table('siswa')->where('id >', 0)->delete();
        $this->db->table('users')->where('id >', 0)->delete();
        $this->db->table('qr_location')->where('id >', 0)->delete();
        $this->db->table('pengaturan')->where('id >', 0)->delete();

        // Enable foreign key checks
        $this->db->enableForeignKeyChecks();

        // Data Admin
        $adminData = [
            'nama'       => 'Administrator',
            'username'   => 'admin',
            'password'   => password_hash('admin123', PASSWORD_BCRYPT),
            'role'       => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('users')->insert($adminData);
        $adminId = $this->db->insertID();

        // Data Guru
        $guruData = [
            [
                'nama'       => 'Ibu Siti Nurhaliza',
                'username'   => 'guru1',
                'password'   => password_hash('guru123', PASSWORD_BCRYPT),
                'role'       => 'guru',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Pak Budi Santoso',
                'username'   => 'guru2',
                'password'   => password_hash('guru123', PASSWORD_BCRYPT),
                'role'       => 'guru',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('users')->insertBatch($guruData);

        // Data Siswa
        $siswaUsers = [
            [
                'nama'       => 'Ahmad Rizki Pratama',
                'username'   => 'ahmad.rizki',
                'password'   => password_hash('siswa123', PASSWORD_BCRYPT),
                'role'       => 'siswa',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Sinta Dwi Cahyani',
                'username'   => 'sinta.cahyani',
                'password'   => password_hash('siswa123', PASSWORD_BCRYPT),
                'role'       => 'siswa',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Muhammad Fatih Habibi',
                'username'   => 'fatih.habibi',
                'password'   => password_hash('siswa123', PASSWORD_BCRYPT),
                'role'       => 'siswa',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Nur Amelia Putri',
                'username'   => 'amelia.putri',
                'password'   => password_hash('siswa123', PASSWORD_BCRYPT),
                'role'       => 'siswa',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Rian Kurniawan',
                'username'   => 'rian.kurniawan',
                'password'   => password_hash('siswa123', PASSWORD_BCRYPT),
                'role'       => 'siswa',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('users')->insertBatch($siswaUsers);

        // Dapatkan semua user siswa yang baru ditambahkan untuk mendapatkan ID yang benar
        $siswaUserRecords = $this->db->table('users')
                                    ->where('role', 'siswa')
                                    ->orderBy('id', 'ASC')
                                    ->get()
                                    ->getResultArray();

        // Tambah data siswa detail dengan user_id yang benar
        $siswaDetails = [];
        $nisData = [
            ['nisn' => '0123456789', 'nis' => '001', 'kelas' => '10 IPA 1'],
            ['nisn' => '0123456790', 'nis' => '002', 'kelas' => '10 IPA 1'],
            ['nisn' => '0123456791', 'nis' => '003', 'kelas' => '10 IPA 2'],
            ['nisn' => '0123456792', 'nis' => '004', 'kelas' => '10 IPA 2'],
            ['nisn' => '0123456793', 'nis' => '005', 'kelas' => '10 IPS 1'],
        ];

        foreach ($siswaUserRecords as $index => $user) {
            if (isset($nisData[$index])) {
                $siswaDetails[] = [
                    'user_id'   => $user['id'],
                    'nisn'      => $nisData[$index]['nisn'],
                    'nis'       => $nisData[$index]['nis'],
                    'kelas'     => $nisData[$index]['kelas'],
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
        }
        $this->db->table('siswa')->insertBatch($siswaDetails);

        // Data QR Location
        $lokasiData = [
            [
                'nama_lokasi' => 'Pintu Masuk Utama',
                'keterangan'  => 'Lokasi QR Code di pintu masuk utama sekolah',
                'aktif'       => 1,
                'created_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama_lokasi' => 'Kantor Tata Usaha',
                'keterangan'  => 'Lokasi QR Code di kantor tata usaha',
                'aktif'       => 1,
                'created_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama_lokasi' => 'Perpustakaan',
                'keterangan'  => 'Lokasi QR Code di perpustakaan sekolah',
                'aktif'       => 1,
                'created_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama_lokasi' => 'Ruang Guru',
                'keterangan'  => 'Lokasi QR Code di ruang guru',
                'aktif'       => 1,
                'created_at'  => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('qr_location')->insertBatch($lokasiData);

        // Data Pengaturan Waktu
        $pengaturanData = [
            'jam_masuk'       => '07:00:00',
            'batas_terlambat' => '07:15:00',
            'jam_pulang'      => '14:30:00',
            'batas_alpha'     => '08:00:00',
            'created_at'      => date('Y-m-d H:i:s'),
        ];
        $this->db->table('pengaturan')->insert($pengaturanData);

        echo "Data awal berhasil dimasukkan!\n";
    }
}
