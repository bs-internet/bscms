<?php

namespace App\Core\Modules\Form\Models;

use CodeIgniter\Model;
use App\Core\Modules\Form\Entities\FormSubmission;

class FormSubmissionModel extends Model
{
    protected $table = 'form_submissions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = FormSubmission::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['form_id', 'ip_address', 'user_agent', 'status'];

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
        'form_id' => 'required|integer',
        'ip_address' => 'required|max_length[45]',
        'status' => 'required|in_list[new,read]',
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
