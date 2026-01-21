<?php

namespace App\Core\Modules\Form\Entities;

use CodeIgniter\Entity\Entity;

class Form extends Entity
{
    protected $datamap = [];
    
    protected $dates = ['created_at', 'updated_at'];
    
    protected $casts = [
        'id' => 'integer',
    ];

    protected $attributes = [
        'id' => null,
        'name' => null,
        'slug' => null,
        'email_to' => null,
        'success_message' => null,
        'created_at' => null,
        'updated_at' => null,
    ];
}
