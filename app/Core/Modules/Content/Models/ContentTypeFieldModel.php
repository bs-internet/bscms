<?php

namespace App\Core\Modules\Content\Models;

use CodeIgniter\Model;
use App\Core\Modules\Content\Entities\ContentTypeField;

class ContentTypeFieldModel extends Model
{
    protected $table = 'content_type_fields';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = ContentTypeField::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'content_type_id',
        'field_key',
        'field_type',
        'field_label',
        'is_required',
        'field_options',
        'sort_order'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = false;

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}

