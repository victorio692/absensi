<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQrLocationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_lokasi' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'aktif' => [
                'type' => 'BOOLEAN',
                'default' => true,
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
        $this->forge->addKey('id', false, true);
        $this->forge->createTable('qr_location');
    }

    public function down()
    {
        $this->forge->dropTable('qr_location');
    }
}
