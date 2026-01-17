<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use App\Enums\SubmissionStatus;

class CreateFormSubmissionsTable extends Migration
{
    public function up()
    {
        $statusValues = implode("','", SubmissionStatus::all());
        
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'form_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
            ],
            'user_agent' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => "ENUM('{$statusValues}')",
                'default' => SubmissionStatus::NEW->value,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('form_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('form_id', 'forms', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('form_submissions');
    }

    public function down()
    {
        $this->forge->dropTable('form_submissions');
    }
}