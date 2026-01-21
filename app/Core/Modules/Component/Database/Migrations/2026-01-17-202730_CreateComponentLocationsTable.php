<?php

namespace App\Core\Modules\Component\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateComponentLocationsTable extends Migration
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
            'location_type' => [
                'type' => 'ENUM',
                'constraint' => ['content_type', 'content', 'category', 'form'],
            ],
            'location_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
        $this->forge->addUniqueKey(['component_id', 'location_type', 'location_id']);
        $this->forge->addForeignKey('component_id', 'components', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('component_locations');
    }

    public function down()
    {
        $this->forge->dropTable('component_locations');
    }
}
