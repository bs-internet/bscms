<?php

namespace App\Core\Modules\Auth\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object'; // Or Entity if we create one
    protected $useSoftDeletes = true; // Based on migration
    protected $allowedFields = ['name', 'slug', 'description'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'slug' => 'required|min_length[3]|max_length[255]|is_unique[roles.slug,id,{id}]',
    ];

    /**
     * Get permissions for a specific role
     */
    public function getPermissions(int $roleId): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('role_permissions');
        $builder->select('permissions.*');
        $builder->join('permissions', 'permissions.id = role_permissions.permission_id');
        $builder->where('role_permissions.role_id', $roleId);

        return $builder->get()->getResult();
    }

    /**
     * Sync permissions for a role
     */
    public function syncPermissions(int $roleId, array $permissionIds)
    {
        $db = \Config\Database::connect();

        // Delete existing
        $db->table('role_permissions')->where('role_id', $roleId)->delete();

        // Insert new
        if (empty($permissionIds)) {
            return;
        }

        $data = [];
        foreach ($permissionIds as $pId) {
            $data[] = [
                'role_id' => $roleId,
                'permission_id' => $pId
            ];
        }

        $db->table('role_permissions')->insertBatch($data);
    }
}
