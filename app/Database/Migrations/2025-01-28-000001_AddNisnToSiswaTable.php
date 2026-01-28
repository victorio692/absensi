<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNisnToSiswaTable extends Migration
{
    public function up()
    {
        // Cek apakah field nisn sudah ada
        if (!$this->db->fieldExists('nisn', 'siswa')) {
            $this->forge->addColumn('siswa', [
                'nisn' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => true,
                    'after'      => 'user_id',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('nisn', 'siswa')) {
            $this->forge->dropColumn('siswa', 'nisn');
        }
    }
}
