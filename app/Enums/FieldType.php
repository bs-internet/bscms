<?php

namespace App\Enums;

enum FieldType: string
{
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case EMAIL = 'email';
    case NUMBER = 'number';
    case SELECT = 'select';
    case CHECKBOX = 'checkbox';
    case RADIO = 'radio';
    case IMAGE = 'image';
    case GALLERY = 'gallery';
    case FILE = 'file';
    case WYSIWYG = 'wysiwyg';
    case REPEATER = 'repeater';

    public function label(): string
    {
        return match($this) {
            self::TEXT => 'Tek Satır Metin',
            self::TEXTAREA => 'Çok Satır Metin',
            self::EMAIL => 'Email',
            self::NUMBER => 'Sayı',
            self::SELECT => 'Açılır Liste',
            self::CHECKBOX => 'Onay Kutusu',
            self::RADIO => 'Radyo Buton',
            self::IMAGE => 'Tek Görsel',
            self::GALLERY => 'Çoklu Görsel',
            self::FILE => 'Dosya',
            self::WYSIWYG => 'Zengin Metin Editörü',
            self::REPEATER => 'Tekrarlayan Alan Grubu',
        };
    }

    public function hasOptions(): bool
    {
        return match($this) {
            self::SELECT, self::RADIO, self::REPEATER => true,
            default => false,
        };
    }

    public function isMedia(): bool
    {
        return match($this) {
            self::IMAGE, self::GALLERY, self::FILE => true,
            default => false,
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
}