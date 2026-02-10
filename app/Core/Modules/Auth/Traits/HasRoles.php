<?php

namespace App\Core\Modules\Auth\Traits;

use App\Core\Modules\Auth\Models\RoleModel;

trait HasRoles
{
    protected ?array $roles = null;
    protected ?array $permissions = null;

    /**
     * Get user's roles
     */
    public function getRoles(): array
    {
        if ($this->roles !== null) {
            return $this->roles;
        }

        if (empty($this->id)) {
            return [];
        }

        $db = \Config\Database::connect();
        $this->roles = $db->table('user_roles')
            ->select('roles.*')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->where('user_roles.user_id', $this->id)
            ->get()
            ->getResult();

        return $this->roles;
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $slug): bool
    {
        $roles = $this->getRoles();

        foreach ($roles as $role) {
            if ($role->slug === $slug) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all permissions from all roles
     */
    public function getPermissions(): array
    {
        if ($this->permissions !== null) {
            return $this->permissions;
        }

        $roles = $this->getRoles();
        if (empty($roles)) {
            $this->permissions = [];
            return [];
        }

        $roleIds = array_column($roles, 'id');

        $db = \Config\Database::connect();
        $this->permissions = $db->table('role_permissions')
            ->select('permissions.*')
            ->join('permissions', 'permissions.id = role_permissions.permission_id')
            ->whereIn('role_permissions.role_id', $roleIds)
            ->get()
            ->getResult();

        return $this->permissions;
    }

    /**
     * Check if user has a specific permission
     */
    public function can(string $permissionSlug): bool
    {
        // Super Admin Bypass
        if ($this->hasRole('super-admin')) {
            return true;
        }

        $permissions = $this->getPermissions();

        foreach ($permissions as $perm) {
            if ($perm->slug === $permissionSlug) {
                return true;
            }
        }

        return false;
    }
}
