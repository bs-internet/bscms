<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Media extends Entity
{
    protected $datamap = [];
    
    protected $dates = ['created_at'];
    
    protected $casts = [
        'id' => 'integer',
        'filesize' => 'integer',
    ];

    protected $attributes = [
        'id' => null,
        'filename' => null,
        'filepath' => null,
        'mimetype' => null,
        'filesize' => null,
        'created_at' => null,
    ];
}