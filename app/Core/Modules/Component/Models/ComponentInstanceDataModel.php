<?php

namespace App\Core\Modules\Component\Models;

use CodeIgniter\Model;
use App\Core\Modules\Component\Entities\ComponentInstanceData;

class ComponentInstanceDataModel extends Model
{
    protected $table = 'component_instance_data';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = ComponentInstanceData::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'component_instance_id',
        'field_key',
        'field_value'
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
