<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ContentType extends Entity
{
    protected $datamap = [];
    
    protected $dates = ['created_at', 'updated_at'];
    
    protected $casts = [
        'id' => 'integer',
        'has_seo_fields' => 'boolean',
        'has_categories' => 'boolean',
    ];

    protected $attributes = [
        'id' => null,
        'name' => null,
        'slug' => null,
        'has_seo_fields' => false,
        'has_categories' => false,
        'created_at' => null,
        'updated_at' => null,
    ];
}