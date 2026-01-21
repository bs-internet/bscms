<?php

namespace App\Core\Modules\Form\Events;

use CodeIgniter\Events\Events;

class FormEvents
{
    public static function register(): void
    {
        Events::on('form_deleted', function (int $formId) {
            $db = \Config\Database::connect();

            // 1. Component locations cleanup
            $db->table('component_locations')
                ->where('location_type', 'form')
                ->where('location_id', $formId)
                ->delete();

            // 2. Form fields cleanup (cascade should handle, but explicit is better)
            $db->table('form_fields')
                ->where('form_id', $formId)
                ->delete();

            // 3. Submissions cleanup (cascade should handle)
            $submissions = $db->table('form_submissions')
                ->where('form_id', $formId)
                ->get()
                ->getResult();

            foreach ($submissions as $submission) {
                $db->table('form_submission_data')
                    ->where('submission_id', $submission->id)
                    ->delete();
            }

            $db->table('form_submissions')
                ->where('form_id', $formId)
                ->delete();

            // 4. Cache invalidation
            cache()->delete("form_{$formId}");
            cache()->deleteMatching("form_*");
        });

        Events::on('form_updated', function (int $formId) {
            cache()->delete("form_{$formId}");
            cache()->deleteMatching("form_*");
        });

        Events::on('form_created', function (int $formId) {
            cache()->deleteMatching("form_*");
        });
    }
}
