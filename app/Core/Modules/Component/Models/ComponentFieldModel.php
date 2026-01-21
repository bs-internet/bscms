<?php

namespace App\Core\Modules\Component\Models;

use CodeIgniter\Model;
use App\Core\Modules\Component\Entities\ComponentField;

class ComponentFieldModel extends Model
{
    protected $table = 'component_fields';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = ComponentField::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'component_id',
        'field_key',
        'field_type',
        'field_label',
        'field_options',
        'is_required',
        'sort_order'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}
