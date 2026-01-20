<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResetTokenToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'reset_token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'default' => null,
            ],
            'reset_expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'reset_token');
        $this->forge->dropColumn('users', 'reset_expires_at');
    }
}
