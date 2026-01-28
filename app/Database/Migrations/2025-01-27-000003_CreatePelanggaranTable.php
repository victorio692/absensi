<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePelanggaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'bulan' => [
                'type' => 'DATE',
                'null' => false,
                'comment' => 'Bulan pelanggaran (format first day of month)',
            ],
            'jenis' => [
                'type'       => 'ENUM',
                'constraint' => ['Terlambat', 'Alpha'],
                'null'       => false,
            ],
            'jumlah' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('siswa_id', 'siswa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['siswa_id', 'bulan', 'jenis']);
        $this->forge->createTable('pelanggaran', true);
    }

    public function down()
    {
        $this->forge->dropTable('pelanggaran', true);
    }
}
