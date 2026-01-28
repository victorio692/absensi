<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQrDailyTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'location_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', false, true);
        $this->forge->addUniqueKey(['location_id', 'tanggal']);
        $this->forge->addForeignKey('location_id', 'qr_location', 'id', '', 'CASCADE');
        $this->forge->createTable('qr_daily');
    }

    public function down()
    {
        $this->forge->dropTable('qr_daily');
    }
}
