<?php

namespace App\Core\Modules\Auth\Validation;

class AuthValidation
{
    public static function loginRules(): array
    {
        return [
            'username' => 'required',
            'password' => 'required'
        ];
    }

    public static function loginMessages(): array
    {
        return [
            'username' => [
                'required' => 'Kullanıcı adı zorunludur.'
            ],
            'password' => [
                'required' => 'Şifre zorunludur.'
            ]
        ];
    }
}
