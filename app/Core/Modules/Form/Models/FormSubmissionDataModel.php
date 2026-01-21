<?php

namespace App\Core\Modules\Form\Models;

use CodeIgniter\Model;
use App\Core\Modules\Form\Entities\FormSubmissionData;

class FormSubmissionDataModel extends Model
{
    protected $table = 'form_submission_data';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = FormSubmissionData::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['submission_id', 'field_key', 'field_value'];

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
        'submission_id' => 'required|integer',
        'field_key' => 'required|max_length[100]',
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
