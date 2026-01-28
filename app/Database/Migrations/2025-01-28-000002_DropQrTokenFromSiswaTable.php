<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropQrTokenFromSiswaTable extends Migration
{
    public function up()
    {
        // Drop column qr_token dari tabel siswa
        if ($this->db->fieldExists('qr_token', 'siswa')) {
            $this->forge->dropColumn('siswa', 'qr_token');
        }
    }

    public function down()
    {
        // Rollback: tambah kembali column qr_token
        $this->forge->addColumn('siswa', [
            'qr_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'kelas',
            ],
        ]);
    }
}
