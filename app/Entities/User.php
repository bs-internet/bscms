<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $datamap = [];
    
    protected $dates = ['created_at', 'updated_at'];
    
    protected $casts = [
        'id' => 'integer',
    ];

    protected $attributes = [
        'id' => null,
        'username' => null,
        'email' => null,
        'password' => null,
        'created_at' => null,
        'updated_at' => null,
    ];
}