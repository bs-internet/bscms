<?php

namespace App\Core\Modules\Menu\Events;

use CodeIgniter\Events\Events;

class MenuEvents
{
    public static function register(): void
    {
        Events::on('menu_deleted', function (int $menuId) {
            $db = \Config\Database::connect();

            // 1. Menu items cleanup
            $db->table('menu_items')
                ->where('menu_id', $menuId)
                ->delete();

            // 2. Cache invalidation
            cache()->delete("menu_{$menuId}");
            cache()->deleteMatching("menu_*");
        });

        Events::on('menu_updated', function (int $menuId) {
            cache()->delete("menu_{$menuId}");
            cache()->deleteMatching("menu_*");
        });

        Events::on('menu_created', function (int $menuId) {
            cache()->deleteMatching("menu_*");
        });

        Events::on('menu_item_changed', function (int $menuId) {
            cache()->deleteMatching("menu_*");
        });
    }
}
