<?php

namespace App\Core\Modules\Component\Entities;

use CodeIgniter\Entity\Entity;
use App\Core\Modules\Component\Enums\ComponentType;

class Component extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at'];
    protected $casts   = [
        'id' => 'integer',
        'type' => 'string'
    ];

    public function getType(): ComponentType
    {
        return ComponentType::from($this->attributes['type']);
    }

    public function setType(ComponentType|string $type): self
    {
        if ($type instanceof ComponentType) {
            $this->attributes['type'] = $type->value;
        } else {
            $this->attributes['type'] = $type;
        }
        return $this;
    }
}

