<?php

namespace App\Core\Modules\Category\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContentCategoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'content_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);

        $this->forge->addKey(['content_id', 'category_id'], true);
        $this->forge->addForeignKey('content_id', 'contents', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('content_categories');
    }

    public function down()
    {
        $this->forge->dropTable('content_categories');
    }
}
