<?php

namespace App\Core\Modules\Media\Enums;

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
            self::LARGE => ['width' => 1024, 'height' => 1024],
            self::MEDIUM => ['width' => 600, 'height' => 600],
            self::SMALL => ['width' => 300, 'height' => 300],
            self::THUMBNAIL => ['width' => 150, 'height' => 150],
            self::FULL => [],
        };
    }

    public function label(): string
    {
        return match($this) {
            self::FULL => 'Orjinal',
            self::LARGE => 'Büyük (1024x1024)',
            self::MEDIUM => 'Orta (600x600)',
            self::SMALL => 'Küçük (300x300)',
            self::THUMBNAIL => 'Thumbnail (150x150)',
        };
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function withDimensions(): array
    {
        return [
            self::LARGE->value,
            self::MEDIUM->value,
            self::SMALL->value,
            self::THUMBNAIL->value,
        ];
    }
}

