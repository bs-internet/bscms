<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ComponentInstance extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at'];
    protected $casts   = [
        'id' => 'integer',
        'component_id' => 'integer',
        'is_global' => 'boolean'
    ];
}