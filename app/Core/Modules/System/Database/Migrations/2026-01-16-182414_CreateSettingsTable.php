<?php

namespace App\Core\Modules\System\Database\Migrations;

use CodeIgniter\Database\Migration;
use App\Core\Modules\System\Enums\SettingGroup;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $groupValues = implode("','", SettingGroup::all());
        
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'setting_key' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'setting_group' => [
                'type' => "ENUM('{$groupValues}')",
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
        $this->forge->addKey('setting_group');
        $this->forge->createTable('settings');
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}

