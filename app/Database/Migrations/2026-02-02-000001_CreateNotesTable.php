<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id'           => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'title'             => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'message'           => [
                'type' => 'LONGTEXT',
            ],
            'type'              => [
                'type'       => 'ENUM',
                'constraint' => ['success', 'error', 'warning', 'info'],
                'default'    => 'info',
            ],
            'is_read'           => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'is_permanent'      => [
                'type'    => 'BOOLEAN',
                'default' => false,
                'comment' => 'Jika true, tidak akan auto-dismiss',
            ],
            'auto_dismiss_in'   => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Detik sampai auto-dismiss (0 = tidak dismiss)',
            ],
            'created_at'        => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'        => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at'        => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', false, true);
        // Only add foreign key if user_id is not null, to avoid issues
        // We'll handle the relationship in the model instead
        $this->forge->addKey('is_read');
        $this->forge->addKey('is_permanent');
        $this->forge->addKey('created_at');

        $this->forge->createTable('notes');
    }

    public function down()
    {
        $this->forge->dropTable('notes');
    }
}
