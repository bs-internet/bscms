<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateComponentInstancesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'component_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'is_global' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('component_id');
        $this->forge->addForeignKey('component_id', 'components', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('component_instances');
    }

    public function down()
    {
        $this->forge->dropTable('component_instances');
    }
}