<?php

namespace App\Enums;

enum ComponentType: string
{
    case GLOBAL = 'global';
    case SPECIFIC = 'specific';

    public function label(): string
    {
        return match($this) {
            self::GLOBAL => 'Global',
            self::SPECIFIC => 'Spesifik',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::GLOBAL => 'Tüm sayfalarda kullanılabilir, tek bir veri seti',
            self::SPECIFIC => 'Belirli içeriklere atanır, her atama için ayrı veri',
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
