<?php

namespace App\Core\Modules\Content\Models;

use CodeIgniter\Model;
use App\Core\Modules\Content\Entities\ContentComponent;

class ContentComponentModel extends Model
{
    protected $table = 'content_components';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = ContentComponent::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'content_id',
        'component_instance_id',
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
