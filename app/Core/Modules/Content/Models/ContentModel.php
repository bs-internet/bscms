<?php

namespace App\Core\Modules\Content\Models;

use CodeIgniter\Model;
use App\Core\Modules\Content\Entities\Content;

class ContentModel extends Model
{
    protected $table = 'contents';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = Content::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'content_type_id',
        'title',
        'slug',
        'status',
        'properties' // New JSON column
    ];

    protected $casts = [
        'properties' => 'json', // Auto-cast JSON to Array/Object
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

