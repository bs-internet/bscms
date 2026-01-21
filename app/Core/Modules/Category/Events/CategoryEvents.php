<?php

namespace App\Core\Modules\Category\Events;

use CodeIgniter\Events\Events;

class CategoryEvents
{
    public static function register(): void
    {
        Events::on('category_deleted', function (int $categoryId) {
            $db = \Config\Database::connect();

            // 1. Component locations cleanup
            $db->table('component_locations')
                ->where('location_type', 'category')
                ->where('location_id', $categoryId)
                ->delete();

            // 2. Content-category relations (should cascade, but explicit is better)
            $db->table('content_categories')
                ->where('category_id', $categoryId)
                ->delete();

            // 3. Cache invalidation
            cache()->delete("category_{$categoryId}");
            cache()->deleteMatching("category_*");
            cache()->deleteMatching("content_list_*"); // Categories affect content lists
        });

        Events::on('category_updated', function (int $categoryId) {
            cache()->delete("category_{$categoryId}");
            cache()->deleteMatching("category_*");
            cache()->deleteMatching("content_list_*");
        });

        Events::on('category_created', function (int $categoryId) {
            cache()->deleteMatching("category_*");
            cache()->deleteMatching("content_list_*");
        });
    }
}
