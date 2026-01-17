<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use App\Enums\ContentStatus;

class CreateContentsTable extends Migration
{
    public function up()
    {
        $statusValues = implode("','", ContentStatus::all());
        
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
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'status' => [
                'type' => "ENUM('{$statusValues}')",
                'default' => ContentStatus::DRAFT->value,
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

        $this->forge->addKey('id', true);
        $this->forge->addKey('content_type_id');
        $this->forge->addKey('slug');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('content_type_id', 'content_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('contents');
    }

    public function down()
    {
        $this->forge->dropTable('contents');
    }
}