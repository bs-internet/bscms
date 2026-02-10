<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsProtectedToContentTypes extends Migration
{
    public function up()
    {
        $this->forge->addColumn('content_types', [
            'is_protected' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'visible'
            ]
        ]);

        // Default protected types
        $db = \Config\Database::connect();
        $db->table('content_types')
            ->whereIn('slug', ['pages', 'posts', 'products'])
            ->update(['is_protected' => 1]);
    }

    public function down()
    {
        $this->forge->dropColumn('content_types', 'is_protected');
    }
}
