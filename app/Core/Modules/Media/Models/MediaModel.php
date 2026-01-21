<?php

namespace App\Core\Modules\Media\Models;

use CodeIgniter\Model;
use App\Core\Modules\Media\Entities\Media;

class MediaModel extends Model
{
    protected $table = 'media';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = Media::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['filename', 'filepath', 'mimetype', 'filesize'];

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
        'filename' => 'required|max_length[255]',
        'filepath' => 'required|max_length[255]',
        'mimetype' => 'required|max_length[100]',
        'filesize' => 'required|integer',
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
