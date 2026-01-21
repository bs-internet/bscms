<?php

namespace App\Core\Modules\Component\Validation;

use App\Core\Modules\Component\Enums\ComponentLocationType;

class ComponentLocationValidation
{
    public static function rules(): array
    {
        $locationTypeList = implode(',', ComponentLocationType::active());
        
        return [
            'location_type' => "required|in_list[{$locationTypeList}]",
            'location_id' => 'required|integer'
        ];
    }

    public static function messages(): array
    {
        return [
            'location_type' => [
                'required' => 'Konum tipi zorunludur.',
                'in_list' => 'Geçersiz konum tipi.'
            ],
            'location_id' => [
                'required' => 'Konum ID zorunludur.',
                'integer' => 'Konum ID sayı olmalıdır.'
            ]
        ];
    }
}

