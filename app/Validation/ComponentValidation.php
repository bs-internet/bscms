<?php

namespace App\Validation;

use App\Enums\ComponentType;

class ComponentValidation
{
    public static function rules(): array
    {
        $typeList = implode(',', ComponentType::all());
        
        return [
            'name' => 'required|max_length[255]',
            'slug' => 'required|max_length[255]|alpha_dash',
            'type' => "required|in_list[{$typeList}]"
        ];
    }

    public static function messages(): array
    {
        return [
            'name' => [
                'required' => 'Bileşen adı zorunludur.',
                'max_length' => 'Bileşen adı en fazla 255 karakter olabilir.'
            ],
            'slug' => [
                'required' => 'Slug alanı zorunludur.',
                'max_length' => 'Slug en fazla 255 karakter olabilir.',
                'alpha_dash' => 'Slug sadece harf, rakam, tire ve alt çizgi içerebilir.'
            ],
            'type' => [
                'required' => 'Bileşen tipi zorunludur.',
                'in_list' => 'Geçersiz bileşen tipi.'
            ]
        ];
    }
}