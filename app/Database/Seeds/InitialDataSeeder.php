<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Enums\SettingGroup;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        $this->createAdminUser();
        $this->createDefaultSettings();
        $this->createDefaultMenus();
    }

    protected function createAdminUser()
    {
        $data = [
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table('users')->insert($data);
    }

    protected function createDefaultSettings()
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

    protected function createDefaultMenus()
    {
        $headerMenu = [
            'name' => 'Ana Menü',
            'location' => 'header'
        ];
        
        $this->db->table('menus')->insert($headerMenu);
        $menuId = $this->db->insertID();

        $menuItems = [
            [
                'menu_id' => $menuId,
                'parent_id' => null,
                'title' => 'Ana Sayfa',
                'url' => '/',
                'sort_order' => 1
            ],
            [
                'menu_id' => $menuId,
                'parent_id' => null,
                'title' => 'Hakkımızda',
                'url' => '/page/hakkimizda',
                'sort_order' => 2
            ],
            [
                'menu_id' => $menuId,
                'parent_id' => null,
                'title' => 'İletişim',
                'url' => '/page/iletisim',
                'sort_order' => 3
            ]
        ];

        foreach ($menuItems as $item) {
            $this->db->table('menu_items')->insert($item);
        }

        $footerMenu = [
            'name' => 'Alt Menü',
            'location' => 'footer'
        ];
        
        $this->db->table('menus')->insert($footerMenu);
    }
}