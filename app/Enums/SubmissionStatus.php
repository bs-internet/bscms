<?php

namespace App\Enums;

enum SubmissionStatus: string
{
    case NEW = 'new';
    case READ = 'read';

    public function label(): string
    {
        return match($this) {
            self::NEW => 'Yeni',
            self::READ => 'Okundu',
        };
    }

    public function badge(): string
    {
        return match($this) {
            self::NEW => 'primary',
            self::READ => 'secondary',
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
