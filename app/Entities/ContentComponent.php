<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ContentComponent extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at'];
    protected $casts   = [
        'id' => 'integer',
        'content_id' => 'integer',
        'component_instance_id' => 'integer',
        'sort_order' => 'integer'
    ];
}