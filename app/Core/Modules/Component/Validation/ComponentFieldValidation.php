<?php

namespace App\Core\Modules\Component\Validation;

use App\Core\Modules\Content\Enums\FieldType;

class ComponentFieldValidation
{
    public static function rules(): array
    {
        $fieldTypeList = implode(',', FieldType::all());
        
        return [
            'field_key' => 'required|max_length[100]|alpha_dash',
            'field_type' => "required|in_list[{$fieldTypeList}]",
            'field_label' => 'required|max_length[255]',
            'is_required' => 'permit_empty|in_list[0,1]',
            'sort_order' => 'permit_empty|integer'
        ];
    }

    public static function messages(): array
    {
        return [
            'field_key' => [
                'required' => 'Alan anahtarı zorunludur.',
                'max_length' => 'Alan anahtarı en fazla 100 karakter olabilir.',
                'alpha_dash' => 'Alan anahtarı sadece harf, rakam, tire ve alt çizgi içerebilir.'
            ],
            'field_type' => [
                'required' => 'Alan tipi zorunludur.',
                'in_list' => 'Geçersiz alan tipi.'
            ],
            'field_label' => [
                'required' => 'Alan etiketi zorunludur.',
                'max_length' => 'Alan etiketi en fazla 255 karakter olabilir.'
            ],
            'is_required' => [
                'in_list' => 'Geçersiz zorunluluk değeri.'
            ],
            'sort_order' => [
                'integer' => 'Sıralama değeri sayı olmalıdır.'
            ]
        ];
    }
}

