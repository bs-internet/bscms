<?php

namespace App\Core\Modules\Component\Entities;

use CodeIgniter\Entity\Entity;
use App\Core\Modules\Component\Enums\ComponentLocationType;

class ComponentLocation extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at'];
    protected $casts   = [
        'id' => 'integer',
        'component_id' => 'integer',
        'location_type' => 'string',
        'location_id' => 'integer'
    ];

    public function getLocationType(): ComponentLocationType
    {
        return ComponentLocationType::from($this->attributes['location_type']);
    }

    public function setLocationType(ComponentLocationType|string $locationType): self
    {
        if ($locationType instanceof ComponentLocationType) {
            $this->attributes['location_type'] = $locationType->value;
        } else {
            $this->attributes['location_type'] = $locationType;
        }
        return $this;
    }
}

