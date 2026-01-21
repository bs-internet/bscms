<?php

namespace App\Core\Modules\Menu\Entities;

use CodeIgniter\Entity\Entity;

class Menu extends Entity
{
    protected $datamap = [];
    
    protected $dates = [];
    
    protected $casts = [
        'id' => 'integer',
    ];

    protected $attributes = [
        'id' => null,
        'name' => null,
        'location' => null,
    ];
}
