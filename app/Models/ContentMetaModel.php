<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\ContentMeta;

class ContentMetaModel extends Model
{
    protected $table = 'content_meta';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = ContentMeta::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'content_id',
        'meta_key',
        'meta_value'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = false;

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}
