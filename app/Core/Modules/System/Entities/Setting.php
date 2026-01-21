<?php

namespace App\Core\Modules\System\Entities;

use CodeIgniter\Entity\Entity;
use App\Core\Modules\System\Enums\SettingGroup;

class Setting extends Entity
{
    protected $datamap = [];
    
    protected $dates = ['created_at', 'updated_at'];
    
    protected $casts = [
        'id' => 'integer',
        'setting_group' => 'enum[' . SettingGroup::class . ']',
    ];

    protected $attributes = [
        'id' => null,
        'setting_key' => null,
        'setting_value' => null,
        'setting_group' => null,
        'created_at' => null,
        'updated_at' => null,
    ];

    public function getGroupLabel(): string
    {
        return $this->setting_group instanceof SettingGroup ? $this->setting_group->label() : '';
    }

    public function getGroupIcon(): string
    {
        return $this->setting_group instanceof SettingGroup ? $this->setting_group->icon() : '';
    }
}

