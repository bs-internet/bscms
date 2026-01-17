<?php

namespace App\Validation;

class UserValidation
{
    public static function rules(bool $isUpdate = false, ?int $id = null): array
    {
        $usernameRule = $isUpdate && $id 
            ? "required|min_length[3]|max_length[100]|is_unique[users.username,id,{$id}]"
            : 'required|min_length[3]|max_length[100]|is_unique[users.username]';

        $emailRule = $isUpdate && $id 
            ? "required|valid_email|is_unique[users.email,id,{$id}]"
            : 'required|valid_email|is_unique[users.email]';

        $passwordRule = $isUpdate ? 'permit_empty|min_length[6]' : 'required|min_length[6]';

        return [
            'username' => $usernameRule,
            'email' => $emailRule,
            'password' => $passwordRule
        ];
    }

    public static function messages(): array
    {
        return [
            'username' => [
                'required' => 'Kullanıcı adı zorunludur.',
                'min_length' => 'Kullanıcı adı en az 3 karakter olmalıdır.',
                'max_length' => 'Kullanıcı adı en fazla 100 karakter olabilir.',
                'is_unique' => 'Bu kullanıcı adı zaten kullanılıyor.'
            ],
            'email' => [
                'required' => 'Email adresi zorunludur.',
                'valid_email' => 'Geçerli bir email adresi giriniz.',
                'is_unique' => 'Bu email adresi zaten kullanılıyor.'
            ],
            'password' => [
                'required' => 'Şifre zorunludur.',
                'min_length' => 'Şifre en az 6 karakter olmalıdır.'
            ]
        ];
    }
}