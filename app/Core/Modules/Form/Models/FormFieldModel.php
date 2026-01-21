<?php

namespace App\Core\Modules\Form\Models;

use CodeIgniter\Model;
use App\Core\Modules\Form\Entities\FormField;

class FormFieldModel extends Model
{
    protected $table = 'form_fields';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = FormField::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['form_id', 'field_key', 'field_type', 'field_label', 'is_required', 'field_options', 'sort_order'];

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
        'form_id' => 'required|integer',
        'field_key' => 'required|max_length[100]',
        'field_type' => 'required|max_length[50]',
        'field_label' => 'required|max_length[255]',
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
