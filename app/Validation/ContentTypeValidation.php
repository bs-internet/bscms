<?php

namespace App\Validation;

class ContentTypeValidation
{
    public static function rules(bool $isUpdate = false, ?int $id = null): array
    {
        $slugRule = $isUpdate && $id 
            ? "required|max_length[100]|is_unique[content_types.slug,id,{$id}]"
            : 'required|max_length[100]|is_unique[content_types.slug]';

        return [
            'name' => 'required|max_length[100]',
            'slug' => $slugRule
        ];
    }

    public static function messages(): array
    {
        return [
            'name' => [
                'required' => 'İsim alanı zorunludur.',
                'max_length' => 'İsim en fazla 100 karakter olabilir.'
            ],
            'slug' => [
                'required' => 'Slug alanı zorunludur.',
                'max_length' => 'Slug en fazla 100 karakter olabilir.',
                'is_unique' => 'Bu slug zaten kullanılıyor.'
            ]
        ];
    }
}
