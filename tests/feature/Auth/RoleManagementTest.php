<?php

namespace Tests\Feature\Auth;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Core\Modules\Auth\Models\UserModel;
use App\Database\Seeds\RoleSeeder;

class RoleManagementTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $seed = RoleSeeder::class;
    protected $namespace = 'App';

    public function testUnauthorizedAccess()
    {
        $result = $this->get('/admin/roles');
        $result->assertRedirectTo('/admin/login');
    }

    public function testEditorCannotAccessRoles()
    {
        // 1. Create Editor User
        $userModel = new UserModel();
        $userId = $userModel->insert([
            'username' => 'editor',
            'email' => 'editor@test.com',
            'password' => '123456'
        ]);

        $db = \Config\Database::connect();
        $role = $db->table('roles')->where('slug', 'editor')->get()->getRow();
        $db->table('user_roles')->insert(['user_id' => $userId, 'role_id' => $role->id]);

        // 2. Mock Session
        $values = [
            'admin_logged_in' => true,
            'admin_user_id' => $userId,
        ];
        session()->set($values);

        // 3. Request
        $result = $this->withSession($values)->get('/admin/roles');

        // 4. Expect Redirect or Error (PermissionFilter redirects back)
        // Since 'back' in test environment might be tricky, usually CI4 redirects to previous.
        // Let's assert status or check if we are NOT on index.
        // PermissionFilter does: return redirect()->back()->with('error', ...)

        $result->assertStatus(302); // Redirect
    }

    public function testSuperAdminCanAccessRoles()
    {
        // 1. Create Super Admin
        $userModel = new UserModel();
        $userId = $userModel->insert([
            'username' => 'admin',
            'email' => 'admin@test.com',
            'password' => '123456'
        ]);

        $db = \Config\Database::connect();
        $role = $db->table('roles')->where('slug', 'super-admin')->get()->getRow();
        $db->table('user_roles')->insert(['user_id' => $userId, 'role_id' => $role->id]);

        // 2. Mock Session
        $values = [
            'admin_logged_in' => true,
            'admin_user_id' => $userId,
        ];

        // 3. Request
        $result = $this->withSession($values)->get('/admin/roles');

        // 4. Assert
        $result->assertStatus(200);
        $result->assertSee('Rol Yönetimi');
    }

    public function testCreateRole()
    {
        // 1. Login as Super Admin
        $userModel = new UserModel();
        $userId = $userModel->insert([
            'username' => 'admin',
            'email' => 'admin@test.com',
            'password' => '123456'
        ]);
        $db = \Config\Database::connect();
        $role = $db->table('roles')->where('slug', 'super-admin')->get()->getRow();
        $db->table('user_roles')->insert(['user_id' => $userId, 'role_id' => $role->id]);

        $values = ['admin_logged_in' => true, 'admin_user_id' => $userId];

        // 2. Post Data
        $result = $this->withSession($values)->post('/admin/roles/store', [
            'name' => 'Moderator',
            'slug' => 'moderator',
            'description' => 'Test Role',
            csrf_token() => csrf_hash()
        ]);

        // 3. Assert
        $result->assertRedirectTo('/admin/roles');
        $this->seeInDatabase('roles', ['slug' => 'moderator']);
    }
}
