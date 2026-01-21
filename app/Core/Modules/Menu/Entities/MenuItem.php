<?php

namespace App\Core\Modules\Menu\Entities;

use CodeIgniter\Entity\Entity;

class MenuItem extends Entity
{
    protected $datamap = [];
    
    protected $dates = [];
    
    protected $casts = [
        'id' => 'integer',
        'menu_id' => 'integer',
        'parent_id' => '?integer',
        'sort_order' => 'integer',
    ];

    protected $attributes = [
        'id' => null,
        'menu_id' => null,
        'parent_id' => null,
        'title' => null,
        'url' => null,
        'sort_order' => 0,
    ];
}
