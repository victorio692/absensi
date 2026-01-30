<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAbsensiTableStatus extends Migration
{
    public function up()
    {
        // Check apakah column sudah ada sebelum menambahkan
        $db = \Config\Database::connect();
        $fields = $db->getFieldData('absensi');
        $fieldNames = array_column($fields, 'name');

        // Tambahkan column jika belum ada
        if (!in_array('source', $fieldNames)) {
            $this->forge->addColumn('absensi', [
                'source' => [
                    'type' => 'ENUM',
                    'constraint' => ['qr', 'manual', 'izin', 'system'],
                    'default' => 'qr',
                    'null' => false,
                    'after' => 'status',
                ],
            ]);
        }

        if (!in_array('keterangan', $fieldNames)) {
            $this->forge->addColumn('absensi', [
                'keterangan' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'source',
                ],
            ]);
        }

        // Update status ENUM
        $db->query("ALTER TABLE `absensi` MODIFY `status` ENUM('hadir','terlambat','izin','sakit','alpha') NULL");
    }

    public function down()
    {
        // Revert to original status
        $this->forge->dropColumn('absensi', ['source', 'keterangan']);
        $this->db->query("ALTER TABLE `absensi` MODIFY `status` ENUM('hadir','terlambat') NULL");
    }
}
