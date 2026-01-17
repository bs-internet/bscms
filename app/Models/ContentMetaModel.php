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
    protected $allowedFields = ['content_id', 'meta_key', 'meta_value'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'content_id' => 'required|integer',
        'meta_key' => 'required|max_length[255]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
}