<?php

namespace Tests\Unit\Auth;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Core\Modules\Auth\Entities\User;
use App\Core\Modules\Auth\Models\UserModel;
use App\Database\Seeds\RoleSeeder;

class PermissionTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $seed = RoleSeeder::class;
    protected $namespace = 'App';

    public function testHasRole()
    {
        // 1. Create a User
        $userModel = new UserModel();
        $userId = $userModel->insert([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $user = $userModel->find($userId);

        // 2. Assign 'editor' role manually (or via a helper if we had one)
        $db = \Config\Database::connect();
        $role = $db->table('roles')->where('slug', 'editor')->get()->getRow();

        $db->table('user_roles')->insert([
            'user_id' => $userId,
            'role_id' => $role->id
        ]);

        // 3. Asset
        $this->assertTrue($user->hasRole('editor'));
        $this->assertFalse($user->hasRole('super-admin'));
    }

    public function testCanPermission()
    {
        // 1. Create User & Assign Editor Role
        $userModel = new UserModel();
        $userId = $userModel->insert([
            'username' => 'editoruser',
            'email' => 'editor@example.com',
            'password' => 'password123'
        ]);
        $user = $userModel->find($userId);

        $db = \Config\Database::connect();
        $role = $db->table('roles')->where('slug', 'editor')->get()->getRow();
        $db->table('user_roles')->insert([
            'user_id' => $userId,
            'role_id' => $role->id
        ]);

        // 2. Assert Standard Permission
        // Editor has 'content.create' from Seeder
        $this->assertTrue($user->can('content.create'));

        // Editor does NOT have 'users.manage' (only Super Admin)
        $this->assertFalse($user->can('users.manage'));
    }

    public function testSuperAdminBypass()
    {
        // 1. Create Super Admin User
        $userModel = new UserModel();
        $userId = $userModel->insert([
            'username' => 'adminuser',
            'email' => 'admin@example.com',
            'password' => 'password123'
        ]);
        $user = $userModel->find($userId);

        $db = \Config\Database::connect();
        $role = $db->table('roles')->where('slug', 'super-admin')->get()->getRow();
        $db->table('user_roles')->insert([
            'user_id' => $userId,
            'role_id' => $role->id
        ]);

        // 2. Assert Bypass
        // Even if 'ultra.secret.permission' doesn't exist, Super Admin might return true 
        // depending on logic. Our logic returns true immediately.
        $this->assertTrue($user->can('any.permission.even.nonexistent'));
    }
}
