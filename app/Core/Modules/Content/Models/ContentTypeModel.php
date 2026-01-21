<?php

namespace App\Core\Modules\Content\Models;

use CodeIgniter\Model;
use App\Core\Modules\Content\Entities\ContentType;

class ContentTypeModel extends Model
{
    protected $table = 'content_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = ContentType::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'slug',
        'has_seo_fields',
        'has_categories',
        'visible'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}

