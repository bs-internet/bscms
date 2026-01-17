<?php

namespace App\Validation;

class CategoryValidation
{
    public static function rules(): array
    {
        return [
            'name' => 'required|max_length[255]',
            'slug' => 'required|max_length[255]'
        ];
    }

    public static function messages(): array
    {
        return [
            'name' => [
                'required' => 'Kategori adı zorunludur.',
                'max_length' => 'Kategori adı en fazla 255 karakter olabilir.'
            ],
            'slug' => [
                'required' => 'Slug alanı zorunludur.',
                'max_length' => 'Slug en fazla 255 karakter olabilir.'
            ]
        ];
    }
}