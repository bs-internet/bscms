<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ContentType extends Entity
{
    protected $datamap = [];
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'has_seo_fields' => 'boolean',
        'has_categories' => 'boolean',
        'visible' => 'boolean',
    ];

    protected $attributes = [
        'has_seo_fields' => false,
        'has_categories' => false,
        'visible' => true,
    ];

    public function isVisible(): bool
    {
        return $this->visible;
    }
}
