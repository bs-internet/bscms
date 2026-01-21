<?php

namespace App\Core\Modules\System\Models;

use CodeIgniter\Model;
use App\Core\Modules\System\Entities\Setting;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = Setting::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['setting_key', 'setting_value', 'setting_group'];

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
        'setting_key' => 'required|max_length[100]|is_unique[settings.setting_key,id,{id}]',
        'setting_group' => 'required|max_length[50]',
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
