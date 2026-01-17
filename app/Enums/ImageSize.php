<?php

namespace App\Enums;

enum ImageSize: string
{
    case FULL = 'full';
    case LARGE = 'large';
    case MEDIUM = 'medium';
    case SMALL = 'small';
    case THUMBNAIL = 'thumbnail';

    public function dimensions(): array
    {
        return match($this) {
            self::THUMBNAIL => ['width' => 150, 'height' => 150],
            self::SMALL => ['width' => 300, 'height' => 300],
            self::MEDIUM => ['width' => 600, 'height' => 600],
            self::LARGE => ['width' => 1024, 'height' => 1024],
            self::FULL => ['width' => null, 'height' => null],
        };
    }

    public function label(): string
    {
        return match($this) {
            self::FULL => 'Orijinal',
            self::LARGE => 'Büyük (1024x1024)',
            self::MEDIUM => 'Orta (600x600)',
            self::SMALL => 'Küçük (300x300)',
            self::THUMBNAIL => 'Küçük Resim (150x150)',
        };
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function withDimensions(): array
    {
        $sizes = [];
        foreach (self::cases() as $case) {
            if ($case !== self::FULL) {
                $sizes[$case->value] = $case->dimensions();
            }
        }
        return $sizes;
    }
}