<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ComponentInstanceData extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at'];
    protected $casts   = [
        'id' => 'integer',
        'component_instance_id' => 'integer'
    ];

    public function getFieldValue()
    {
        if (empty($this->attributes['field_value'])) {
            return null;
        }

        $decoded = json_decode($this->attributes['field_value'], true);
        return $decoded !== null ? $decoded : $this->attributes['field_value'];
    }

    public function setFieldValue($value): self
    {
        if (is_array($value)) {
            $this->attributes['field_value'] = json_encode($value);
        } else {
            $this->attributes['field_value'] = $value;
        }
        return $this;
    }
}