<?php

namespace App\Core\Modules\Form\Entities;

use CodeIgniter\Entity\Entity;

class FormSubmissionData extends Entity
{
    protected $datamap = [];
    
    protected $dates = [];
    
    protected $casts = [
        'id' => 'integer',
        'submission_id' => 'integer',
    ];

    protected $attributes = [
        'id' => null,
        'submission_id' => null,
        'field_key' => null,
        'field_value' => null,
    ];
}
