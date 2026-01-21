<?php

namespace App\Core\Modules\Form\Events;

use CodeIgniter\Events\Events;

class FormEvents
{
    public static function register(): void
    {
        Events::on('form_deleted', function ($formId) {
            self::cleanupComponentLocations($formId);
        });
    }

    protected static function cleanupComponentLocations($formId): void
    {
        $db = \Config\Database::connect();

        // Delete from component_locations
        $db->table('component_locations')
            ->where('location_type', 'form')
            ->where('location_id', $formId)
            ->delete();
    }
}

