<?php

namespace App\Validation;

class MenuValidation
{
    public static function rules(bool $isUpdate = false, ?int $id = null): array
    {
        $locationRule = $isUpdate && $id 
            ? "required|max_length[100]|is_unique[menus.location,id,{$id}]"
            : 'required|max_length[100]|is_unique[menus.location]';

        return [
            'name' => 'required|max_length[100]',
            'location' => $locationRule
        ];
    }

    public static function messages(): array
    {
        return [
            'name' => [
                'required' => 'Menü adı zorunludur.',
                'max_length' => 'Menü adı en fazla 100 karakter olabilir.'
            ],
            'location' => [
                'required' => 'Konum alanı zorunludur.',
                'max_length' => 'Konum en fazla 100 karakter olabilir.',
                'is_unique' => 'Bu konum zaten kullanılıyor.'
            ]
        ];
    }
}