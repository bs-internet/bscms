<?php

namespace App\Enums;

enum ComponentLocationType: string
{
    case CONTENT_TYPE = 'content_type';
    case CONTENT = 'content';
    case CATEGORY = 'category';
    case FORM = 'form';

    public function label(): string
    {
        return match($this) {
            self::CONTENT_TYPE => 'İçerik Türü',
            self::CONTENT => 'İçerik',
            self::CATEGORY => 'Kategori',
            self::FORM => 'Form',
        };
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }

    public static function active(): array
    {
        return [
            self::CONTENT_TYPE->value,
            self::CONTENT->value,
        ];
    }
}
