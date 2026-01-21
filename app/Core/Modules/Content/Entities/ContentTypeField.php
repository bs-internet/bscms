<?php

namespace App\Core\Modules\Content\Entities;

use CodeIgniter\Entity\Entity;
use App\Core\Modules\Content\Enums\FieldType;

class ContentTypeField extends Entity
{
    protected $datamap = [];
    protected $dates = [];
    protected $casts = [
        'content_type_id' => 'int',
        'field_type' => 'enum[' . FieldType::class . ']',
        'is_required' => 'boolean',
        'sort_order' => 'int',
    ];

    public function getFieldOptionsAttribute(?string $value): ?array
    {
        if (is_null($value)) return null;
        $decoded = json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }

    public function setFieldOptionsAttribute($value): self
    {
        $this->attributes['field_options'] = is_array($value) ? json_encode($value) : $value;
        return $this;
    }

    public function getFieldTypeLabel(): string
    {
        return $this->field_type->label();
    }

    public function hasOptions(): bool
    {
        return $this->field_type->hasOptions();
    }

    public function isMedia(): bool
    {
        return $this->field_type->isMedia();
    }
}


