<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\MenuItem;

class MenuItemModel extends Model
{
    protected $table = 'menu_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = MenuItem::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['menu_id', 'parent_id', 'title', 'url', 'sort_order'];

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
        'menu_id' => 'required|integer',
        'title' => 'required|max_length[255]',
        'url' => 'required|max_length[255]',
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