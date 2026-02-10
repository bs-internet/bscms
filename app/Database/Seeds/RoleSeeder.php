<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Create Roles
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Tam yetkili yönetici',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
                'description' => 'İçerik yönetimi yapabilir',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Check if roles exist
        foreach ($roles as $role) {
            $existing = $db->table('roles')->where('slug', $role['slug'])->get()->getRow();
            if (!$existing) {
                $db->table('roles')->insert($role);
            }
        }

        // 2. Create Permissions
        $permissions = [
            // Content
            ['name' => 'İçerik Görüntüleme', 'slug' => 'content.view', 'module' => 'content'],
            ['name' => 'İçerik Oluşturma', 'slug' => 'content.create', 'module' => 'content'],
            ['name' => 'İçerik Düzenleme', 'slug' => 'content.edit', 'module' => 'content'],
            ['name' => 'İçerik Silme', 'slug' => 'content.delete', 'module' => 'content'],
            ['name' => 'İçerik Yayınlama', 'slug' => 'content.publish', 'module' => 'content'],

            // Media
            ['name' => 'Medya Yönetimi', 'slug' => 'media.manage', 'module' => 'media'],

            // Settings
            ['name' => 'Ayarları Yönetme', 'slug' => 'settings.manage', 'module' => 'system'],

            // Users/Roles
            ['name' => 'Kullanıcı Yönetimi', 'slug' => 'users.manage', 'module' => 'auth'],
            ['name' => 'Rol Yönetimi', 'slug' => 'roles.manage', 'module' => 'auth'],
        ];

        foreach ($permissions as $perm) {
            $perm['created_at'] = date('Y-m-d H:i:s');
            $perm['updated_at'] = date('Y-m-d H:i:s');

            $existing = $db->table('permissions')->where('slug', $perm['slug'])->get()->getRow();
            if (!$existing) {
                $db->table('permissions')->insert($perm);
            }
        }

        // 3. Assign Permissions to Editor
        $editorRole = $db->table('roles')->where('slug', 'editor')->get()->getRow();
        if ($editorRole) {
            $editorPerms = [
                'content.view',
                'content.create',
                'content.edit',
                'media.manage'
            ];

            foreach ($editorPerms as $slug) {
                $perm = $db->table('permissions')->where('slug', $slug)->get()->getRow();
                if ($perm) {
                    // Check duplicate
                    $exists = $db->table('role_permissions')
                        ->where('role_id', $editorRole->id)
                        ->where('permission_id', $perm->id)
                        ->countAllResults();

                    if ($exists == 0) {
                        $db->table('role_permissions')->insert([
                            'role_id' => $editorRole->id,
                            'permission_id' => $perm->id
                        ]);
                    }
                }
            }
        }

        // 4. Assign Super Admin to first user (if exists)
        $superAdminRole = $db->table('roles')->where('slug', 'super-admin')->get()->getRow();
        $firstUser = $db->table('users')->orderBy('id', 'ASC')->get()->getRow();

        if ($superAdminRole && $firstUser) {
            $exists = $db->table('user_roles')
                ->where('user_id', $firstUser->id)
                ->where('role_id', $superAdminRole->id)
                ->countAllResults();

            if ($exists == 0) {
                $db->table('user_roles')->insert([
                    'user_id' => $firstUser->id,
                    'role_id' => $superAdminRole->id
                ]);
            }
        }
    }
}
