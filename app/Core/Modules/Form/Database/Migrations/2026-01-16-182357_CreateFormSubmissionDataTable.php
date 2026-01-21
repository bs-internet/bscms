<?php

namespace App\Core\Modules\Form\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFormSubmissionDataTable extends Migration
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
            'submission_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'field_key' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'field_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('submission_id');
        $this->forge->addForeignKey('submission_id', 'form_submissions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('form_submission_data');
    }

    public function down()
    {
        $this->forge->dropTable('form_submission_data');
    }
}
