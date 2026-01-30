<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIzinSakitTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'siswa_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'jenis' => [
                'type' => 'ENUM',
                'constraint' => ['izin', 'sakit'],
                'null' => false,
            ],
            'alasan' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'bukti_file' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
                'null' => false,
            ],
            'catatan_admin' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->createTable('izin_sakit');
    }

    public function down()
    {
        $this->forge->dropTable('izin_sakit');
    }
}
