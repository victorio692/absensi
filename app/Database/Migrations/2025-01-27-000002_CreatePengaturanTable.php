<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengaturanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'jam_masuk' => [
                'type' => 'TIME',
                'null' => false,
                'comment' => 'Jam dimulainya absensi masuk',
            ],
            'batas_terlambat' => [
                'type' => 'TIME',
                'null' => false,
                'comment' => 'Batas maksimal jam masuk sebelum dianggap terlambat',
            ],
            'jam_pulang' => [
                'type' => 'TIME',
                'null' => false,
                'comment' => 'Jam mulai bisa absensi pulang',
            ],
            'batas_alpha' => [
                'type' => 'TIME',
                'null' => false,
                'comment' => 'Batas jam untuk dipanggil alpha otomatis',
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
        $this->forge->createTable('pengaturan', true);

        // Insert default values
        $this->db->table('pengaturan')->insert([
            'jam_masuk'       => '07:00:00',
            'batas_terlambat' => '08:00:00',
            'jam_pulang'      => '15:00:00',
            'batas_alpha'     => '10:00:00',
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('pengaturan', true);
    }
}
