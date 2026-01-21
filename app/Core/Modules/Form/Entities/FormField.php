<?php

namespace App\Core\Modules\Form\Entities;

use CodeIgniter\Entity\Entity;
use App\Core\Modules\Content\Enums\FieldType;

class FormField extends Entity
{
    protected $datamap = [];
    
    protected $dates = [];
    
    protected $casts = [
        'id' => 'integer',
        'form_id' => 'integer',
        'is_required' => 'boolean',
        'sort_order' => 'integer',
        'field_type' => 'enum[' . FieldType::class . ']',
    ];

    protected $attributes = [
        'id' => null,
        'form_id' => null,
        'field_key' => null,
        'field_type' => null,
        'field_label' => null,
        'is_required' => false,
        'field_options' => null,
        'sort_order' => 0,
    ];

    public function getFieldOptionsAttribute(?string $value): ?array
    {
        if (is_null($value)) {
            return null;
        }

        $decoded = json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }

    public function setFieldOptionsAttribute($value): self
    {
        if (is_array($value) || is_object($value)) {
            $this->attributes['field_options'] = json_encode($value);
        } else {
            $this->attributes['field_options'] = $value;
        }
        
        return $this;
    }

    public function getFieldTypeLabel(): string
    {
        return $this->field_type instanceof FieldType ? $this->field_type->label() : '';
    }

    public function hasOptions(): bool
    {
        return $this->field_type instanceof FieldType && $this->field_type->hasOptions();
    }

    public function isMedia(): bool
    {
        return $this->field_type instanceof FieldType && $this->field_type->isMedia();
    }
}

