<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\ComponentInstance;

class ComponentInstanceModel extends Model
{
    protected $table = 'component_instances';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = ComponentInstance::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'component_id',
        'title',
        'is_global'
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