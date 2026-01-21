<?php

namespace App\Core\Modules\Component\Models;

use CodeIgniter\Model;
use App\Core\Modules\Component\Entities\ComponentLocation;

class ComponentLocationModel extends Model
{
    protected $table = 'component_locations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = ComponentLocation::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'component_id',
        'location_type',
        'location_id'
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
