<?php

namespace App\Core\Modules\Category\Events;

use CodeIgniter\Events\Events;

class CategoryEvents
{
    public static function register(): void
    {
        Events::on('category_deleted', function ($categoryId) {
            self::cleanupComponentLocations($categoryId);
        });
    }

    protected static function cleanupComponentLocations($categoryId): void
    {
        $db = \Config\Database::connect();

        // Delete from component_locations
        $db->table('component_locations')
            ->where('location_type', 'category')
            ->where('location_id', $categoryId)
            ->delete();
    }
}

