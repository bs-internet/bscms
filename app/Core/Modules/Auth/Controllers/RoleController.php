<?php

namespace App\Core\Modules\Auth\Controllers;

use App\Core\Shared\Controllers\BaseController;
use App\Core\Modules\Auth\Models\RoleModel;
use App\Core\Modules\Auth\Models\PermissionModel;

class RoleController extends BaseController
{
    protected RoleModel $roleModel;
    protected PermissionModel $permissionModel;

    public function __construct()
    {
        // Permission Check applied via Filter, but double check in constructor if needed?
        // No, Filter is best.
        $this->roleModel = new RoleModel();
        $this->permissionModel = new PermissionModel();
    }

    public function index()
    {
        $roles = $this->roleModel->findAll();

        return view('App\Core\Modules\Auth\Views\roles\index', [
            'roles' => $roles
        ]);
    }

    public function create()
    {
        return view('App\Core\Modules\Auth\Views\roles\form', [
            'role' => null,
            'title' => 'Yeni Rol Ekle'
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        // Auto-generate slug if not present
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = url_title($data['name'], '-', true);
        }

        if (!$this->roleModel->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $this->roleModel->errors());
        }

        return redirect()->to('/admin/roles')->with('success', 'Rol başarıyla oluşturuldu.');
    }

    public function edit(int $id)
    {
        $role = $this->roleModel->find($id);

        if (!$role) {
            return redirect()->to('/admin/roles')->with('error', 'Rol bulunamadı.');
        }

        $allPermissions = $this->permissionModel->getAllGrouped();
        $rolePermissions = $this->roleModel->getPermissions($id);
        $rolePermissionIds = array_column($rolePermissions, 'id');

        return view('App\Core\Modules\Auth\Views\roles\form', [
            'role' => $role,
            'title' => 'Rol Düzenle',
            'allPermissions' => $allPermissions,
            'rolePermissionIds' => $rolePermissionIds,
        ]);
    }

    public function update(int $id)
    {
        $role = $this->roleModel->find($id);

        if (!$role) {
            return redirect()->to('/admin/roles')->with('error', 'Rol bulunamadı.');
        }

        $data = $this->request->getPost();

        // Basic update
        if (!$this->roleModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->roleModel->errors());
        }

        // Sync Permissions
        $permissions = $this->request->getPost('permissions') ?? [];
        $this->roleModel->syncPermissions($id, $permissions);

        return redirect()->to('/admin/roles')->with('success', 'Rol güncellendi.');
    }

    public function delete(int $id)
    {
        $role = $this->roleModel->find($id);

        if (!$role) {
            return redirect()->back()->with('error', 'Rol bulunamadı.');
        }

        if ($role->slug === 'super-admin') {
            return redirect()->back()->with('error', 'Süper Admin rolü silinemez.');
        }

        $this->roleModel->delete($id);

        return redirect()->to('/admin/roles')->with('success', 'Rol silindi.');
    }
}
