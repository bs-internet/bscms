<?php

namespace App\Core\Modules\Component\Events;

use CodeIgniter\Events\Events;

class ComponentEvents
{
    public static function register(): void
    {
        Events::on('component_deleted', function (int $componentId) {
            $db = \Config\Database::connect();

            // 1. Component locations cleanup
            $db->table('component_locations')
                ->where('component_id', $componentId)
                ->delete();

            // 2. Component fields cleanup
            $db->table('component_fields')
                ->where('component_id', $componentId)
                ->delete();

            // 3. Component instances cleanup
            $instances = $db->table('component_instances')
                ->where('component_id', $componentId)
                ->get()
                ->getResult();

            foreach ($instances as $instance) {
                // Delete instance data
                $db->table('component_instance_data')
                    ->where('component_instance_id', $instance->id)
                    ->delete();

                // Delete content_components relations
                $db->table('content_components')
                    ->where('component_instance_id', $instance->id)
                    ->delete();
            }

            // Delete instances
            $db->table('component_instances')
                ->where('component_id', $componentId)
                ->delete();

            // 4. Cache invalidation
            cache()->delete("component_{$componentId}");
            cache()->deleteMatching("component_*");
            cache()->deleteMatching("content_*"); // Components affect content
        });

        Events::on('component_updated', function (int $componentId) {
            cache()->delete("component_{$componentId}");
            cache()->deleteMatching("component_*");
            cache()->deleteMatching("content_*");
        });

        Events::on('component_created', function (int $componentId) {
            cache()->deleteMatching("component_*");
        });
    }
}
