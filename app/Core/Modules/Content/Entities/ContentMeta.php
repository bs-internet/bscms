<?php

namespace App\Core\Modules\Content\Entities;

use CodeIgniter\Entity\Entity;

class ContentMeta extends Entity
{
    protected $datamap = [];
    protected $dates = [];
    protected $casts = [
        'content_id' => 'int',
    ];
}

