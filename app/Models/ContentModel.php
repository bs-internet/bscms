<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Content;

class ContentModel extends Model
{
    protected $table = 'contents';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = Content::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['content_type_id', 'title', 'slug', 'status'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'content_type_id' => 'required|integer',
        'title' => 'required|max_length[255]',
        'slug' => 'required|max_length[255]',
        'status' => 'required|in_list[draft,published,archived]',
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