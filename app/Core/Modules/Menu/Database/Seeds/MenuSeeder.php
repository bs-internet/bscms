<?php

namespace App\Core\Modules\Menu\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
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
