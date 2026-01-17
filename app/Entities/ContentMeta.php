<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ContentMeta extends Entity
{
    protected $datamap = [];
    
    protected $dates = [];
    
    protected $casts = [
        'id' => 'integer',
        'content_id' => 'integer',
    ];

    protected $attributes = [
        'id' => null,
        'content_id' => null,
        'meta_key' => null,
        'meta_value' => null,
    ];

    public function getMetaValueAttribute(?string $value): mixed
    {
        if (is_null($value)) {
            return null;
        }

        $decoded = json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    public function setMetaValueAttribute($value): self
    {
        if (is_array($value) || is_object($value)) {
            $this->attributes['meta_value'] = json_encode($value);
        } else {
            $this->attributes['meta_value'] = $value;
        }
        
        return $this;
    }
}