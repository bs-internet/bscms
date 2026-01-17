<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContentMetaTable extends Migration
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
            'content_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'meta_key' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'meta_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('content_id');
        $this->forge->addKey('meta_key');
        $this->forge->addForeignKey('content_id', 'contents', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('content_meta');
    }

    public function down()
    {
        $this->forge->dropTable('content_meta');
    }
}