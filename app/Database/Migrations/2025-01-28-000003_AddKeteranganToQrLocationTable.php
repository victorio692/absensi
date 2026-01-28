<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKeteranganToQrLocationTable extends Migration
{
    public function up()
    {
        // Check if column already exists
        if (!$this->db->fieldExists('keterangan', 'qr_location')) {
            $this->forge->addColumn('qr_location', [
                'keterangan' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('keterangan', 'qr_location')) {
            $this->forge->dropColumn('qr_location', 'keterangan');
        }
    }
}
