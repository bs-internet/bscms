<?php

namespace App\Core\Modules\Auth\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $allowedFields = ['name', 'slug', 'module'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'slug' => 'required|min_length[3]|max_length[255]|is_unique[permissions.slug,id,{id}]',
        'module' => 'required',
    ];

    /**
     * Get all permissions grouped by module
     */
    public function getAllGrouped(): array
    {
        $permissions = $this->findAll();
        $grouped = [];

        foreach ($permissions as $perm) {
            $grouped[$perm->module][] = $perm;
        }

        return $grouped;
    }
}
