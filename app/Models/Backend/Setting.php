<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    public function getValueAttribute($value)
    {
        if ($this->type === 'json') {
            return json_decode($value, true) ?? [];
        }

        return $value;
    }

    public function setValueAttribute($value): void
    {
        if (is_array($value)) {
            $this->attributes['value'] = json_encode($value);
            $this->attributes['type'] = 'json';
            return;
        }

        $this->attributes['value'] = $value;
        $this->attributes['type'] = 'string';
    }
}
