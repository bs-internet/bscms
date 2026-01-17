<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Category extends Entity
{
    protected $datamap = [];
    
    protected $dates = ['created_at', 'updated_at'];
    
    protected $casts = [
        'id' => 'integer',
        'content_type_id' => 'integer',
        'parent_id' => '?integer',
        'sort_order' => 'integer',
    ];

    protected $attributes = [
        'id' => null,
        'content_type_id' => null,
        'name' => null,
        'slug' => null,
        'parent_id' => null,
        'sort_order' => 0,
        'created_at' => null,
        'updated_at' => null,
    ];
}