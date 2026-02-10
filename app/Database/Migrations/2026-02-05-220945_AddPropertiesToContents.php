<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPropertiesToContents extends Migration
{
    public function up()
    {
        $this->forge->addColumn('contents', [
            'properties' => [
                'type' => 'TEXT',
                'null' => true,
                'default' => null,
                'after' => 'status' // Place it after 'status' column
            ],
        ]);

        // Optimize: If using MySQL 5.7+ or PostgreSQL we could use JSON type, 
        // but TEXT is safer for SQLite/MariaDB compatibility in this specific setup.
        // CI4 casts handle the conversion anyway.
    }

    public function down()
    {
        $this->forge->dropColumn('contents', 'properties');
    }
}
