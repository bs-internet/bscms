<?php

namespace App\Core\Modules\Component\Models;

use CodeIgniter\Model;
use App\Core\Modules\Component\Entities\Component;

class ComponentModel extends Model
{
    protected $table = 'components';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = Component::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'slug',
        'description',
        'type'
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
