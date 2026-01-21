<?php

namespace App\Core\Modules\Form\Validation;

class FormValidation
{
    public static function rules(bool $isUpdate = false, ?int $id = null): array
    {
        $slugRule = $isUpdate && $id 
            ? "required|max_length[100]|is_unique[forms.slug,id,{$id}]"
            : 'required|max_length[100]|is_unique[forms.slug]';

        return [
            'name' => 'required|max_length[100]',
            'slug' => $slugRule,
            'email_to' => 'required|valid_email'
        ];
    }

    public static function messages(): array
    {
        return [
            'name' => [
                'required' => 'Form adı zorunludur.',
                'max_length' => 'Form adı en fazla 100 karakter olabilir.'
            ],
            'slug' => [
                'required' => 'Slug alanı zorunludur.',
                'max_length' => 'Slug en fazla 100 karakter olabilir.',
                'is_unique' => 'Bu slug zaten kullanılıyor.'
            ],
            'email_to' => [
                'required' => 'Email adresi zorunludur.',
                'valid_email' => 'Geçerli bir email adresi giriniz.'
            ]
        ];
    }
}
