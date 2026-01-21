<?php

namespace App\Core\Modules\Component\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateComponentFieldsTable extends Migration
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
            'field_key' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'field_type' => [
                'type' => 'ENUM',
                'constraint' => ['text', 'textarea', 'email', 'number', 'select', 'checkbox', 'radio', 'image', 'gallery', 'file', 'wysiwyg', 'repeater'],
            ],
            'field_label' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'field_options' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_required' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
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
        $this->forge->addUniqueKey(['component_id', 'field_key']);
        $this->forge->addForeignKey('component_id', 'components', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('component_fields');
    }

    public function down()
    {
        $this->forge->dropTable('component_fields');
    }
}
