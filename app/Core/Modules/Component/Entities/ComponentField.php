<?php

namespace App\Core\Modules\Component\Entities;

use CodeIgniter\Entity\Entity;
use App\Core\Modules\Content\Enums\FieldType;

class ComponentField extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at'];
    protected $casts   = [
        'id' => 'integer',
        'component_id' => 'integer',
        'field_type' => 'string',
        'is_required' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function getFieldType(): FieldType
    {
        return FieldType::from($this->attributes['field_type']);
    }

    public function setFieldType(FieldType|string $fieldType): self
    {
        if ($fieldType instanceof FieldType) {
            $this->attributes['field_type'] = $fieldType->value;
        } else {
            $this->attributes['field_type'] = $fieldType;
        }
        return $this;
    }

    public function getFieldOptions(): ?array
    {
        if (empty($this->attributes['field_options'])) {
            return null;
        }
        
        $decoded = json_decode($this->attributes['field_options'], true);
        return is_array($decoded) ? $decoded : null;
    }

    public function setFieldOptions(?array $options): self
    {
        $this->attributes['field_options'] = $options ? json_encode($options) : null;
        return $this;
    }
}

