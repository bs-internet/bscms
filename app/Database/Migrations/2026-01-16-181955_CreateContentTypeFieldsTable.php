<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use App\Enums\FieldType;

class CreateContentTypeFieldsTable extends Migration
{
    public function up()
    {
        $fieldTypeValues = implode("','", FieldType::all());
        
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'content_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'field_key' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'field_type' => [
                'type' => "ENUM('{$fieldTypeValues}')",
            ],
            'field_label' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'is_required' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'field_options' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('content_type_id');
        $this->forge->addKey('sort_order');
        $this->forge->addForeignKey('content_type_id', 'content_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('content_type_fields');
    }

    public function down()
    {
        $this->forge->dropTable('content_type_fields');
    }
}