<?php

namespace App\Core\Modules\Component\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateComponentInstanceDataTable extends Migration
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
            'component_instance_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'field_key' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'field_value' => [
                'type' => 'LONGTEXT',
                'null' => true,
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
        $this->forge->addKey('component_instance_id');
        $this->forge->addUniqueKey(['component_instance_id', 'field_key']);
        $this->forge->addForeignKey('component_instance_id', 'component_instances', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('component_instance_data');
    }

    public function down()
    {
        $this->forge->dropTable('component_instance_data');
    }
}
