<?php

namespace App\Core\Modules\System\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Core\Modules\System\Enums\SettingGroup;

class SystemSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // General
            ['setting_key' => 'site_title', 'setting_value' => 'BSCMS', 'setting_group' => SettingGroup::GENERAL->value],
            ['setting_key' => 'site_description', 'setting_value' => 'Modern İçerik Yönetim Sistemi', 'setting_group' => SettingGroup::GENERAL->value],
            ['setting_key' => 'logo', 'setting_value' => '', 'setting_group' => SettingGroup::GENERAL->value],
            ['setting_key' => 'favicon', 'setting_value' => '', 'setting_group' => SettingGroup::GENERAL->value],

            // Contact
            ['setting_key' => 'phone', 'setting_value' => '', 'setting_group' => SettingGroup::CONTACT->value],
            ['setting_key' => 'email', 'setting_value' => 'info@example.com', 'setting_group' => SettingGroup::CONTACT->value],
            ['setting_key' => 'address', 'setting_value' => '', 'setting_group' => SettingGroup::CONTACT->value],
            ['setting_key' => 'working_hours', 'setting_value' => '', 'setting_group' => SettingGroup::CONTACT->value],

            // Social
            ['setting_key' => 'facebook_url', 'setting_value' => '', 'setting_group' => SettingGroup::SOCIAL->value],
            ['setting_key' => 'instagram_url', 'setting_value' => '', 'setting_group' => SettingGroup::SOCIAL->value],
            ['setting_key' => 'twitter_url', 'setting_value' => '', 'setting_group' => SettingGroup::SOCIAL->value],
            ['setting_key' => 'linkedin_url', 'setting_value' => '', 'setting_group' => SettingGroup::SOCIAL->value],

            // SEO
            ['setting_key' => 'google_analytics', 'setting_value' => '', 'setting_group' => SettingGroup::SEO->value],
            ['setting_key' => 'google_tag_manager', 'setting_value' => '', 'setting_group' => SettingGroup::SEO->value],
            ['setting_key' => 'meta_keywords', 'setting_value' => '', 'setting_group' => SettingGroup::SEO->value],

            // SMTP
            ['setting_key' => 'smtp_host', 'setting_value' => '', 'setting_group' => SettingGroup::SMTP->value],
            ['setting_key' => 'smtp_port', 'setting_value' => '587', 'setting_group' => SettingGroup::SMTP->value],
            ['setting_key' => 'smtp_user', 'setting_value' => '', 'setting_group' => SettingGroup::SMTP->value],
            ['setting_key' => 'smtp_password', 'setting_value' => '', 'setting_group' => SettingGroup::SMTP->value],
        ];

        foreach ($settings as $setting) {
            $setting['created_at'] = date('Y-m-d H:i:s');
            $setting['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('settings')->insert($setting);
        }
    }
}
