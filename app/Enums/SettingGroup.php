<?php

namespace App\Enums;

enum SettingGroup: string
{
    case GENERAL = 'general';
    case CONTACT = 'contact';
    case SOCIAL = 'social';
    case SEO = 'seo';
    case SMTP = 'smtp';

    public function label(): string
    {
        return match($this) {
            self::GENERAL => 'Genel Ayarlar',
            self::CONTACT => 'İletişim Bilgileri',
            self::SOCIAL => 'Sosyal Medya',
            self::SEO => 'SEO Ayarları',
            self::SMTP => 'E-posta Ayarları',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::GENERAL => 'settings',
            self::CONTACT => 'phone',
            self::SOCIAL => 'share-2',
            self::SEO => 'search',
            self::SMTP => 'mail',
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
