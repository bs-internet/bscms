<?php

namespace App\Core\Modules\Content\Validation;

use App\Core\Modules\Content\Enums\ContentStatus;

class ContentValidation
{
    public static function rules(): array
    {
        $statusList = implode(',', ContentStatus::all());

        return [
            'title' => 'required|max_length[255]',
            'slug' => 'required|max_length[255]',
            'status' => "required|in_list[{$statusList}]"
        ];
    }

    public static function messages(): array
    {
        return [
            'title' => [
                'required' => 'Başlık alanı zorunludur.',
                'max_length' => 'Başlık en fazla 255 karakter olabilir.'
            ],
            'slug' => [
                'required' => 'Slug alanı zorunludur.',
                'max_length' => 'Slug en fazla 255 karakter olabilir.'
            ],
            'status' => [
                'required' => 'Durum alanı zorunludur.',
                'in_list' => 'Geçersiz durum değeri.'
            ]
        ];
    }
}


