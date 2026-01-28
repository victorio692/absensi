<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAbsensiTableAddLocation extends Migration
{
    /**
     * Update tabel absensi untuk menambahkan location_id
     * Mencatat lokasi mana siswa melakukan scan masuk
     */
    public function up()
    {
        // Cek apakah field location_id sudah ada
        if (!$this->db->fieldExists('location_id', 'absensi')) {
            $this->forge->addColumn('absensi', [
                'location_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                    'after'      => 'siswa_id',
                    'comment'    => 'Lokasi QR Code yang di-scan',
                ],
            ]);

            // Tambah foreign key jika table sudah ada
            $this->db->query('
                ALTER TABLE `absensi` 
                ADD CONSTRAINT `fk_absensi_location` 
                FOREIGN KEY (`location_id`) REFERENCES `qr_location`(`id`) 
                ON DELETE SET NULL ON UPDATE CASCADE
            ');
        }
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `absensi` DROP FOREIGN KEY `fk_absensi_location`');
        $this->forge->dropColumn('absensi', 'location_id');
    }
}
