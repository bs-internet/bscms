<?php

namespace Tests\Database;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Database\Seeds\RoleSeeder;

class SeederTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';

    public function testRoleSeeder()
    {
        // Run Seeder
        $seeder = new RoleSeeder();
        $seeder->run();

        // Check Roles
        $this->seeInDatabase('roles', ['slug' => 'super-admin']);
        $this->seeInDatabase('roles', ['slug' => 'editor']);

        // Check Permissions
        $this->seeInDatabase('permissions', ['slug' => 'content.create']);
        $this->seeInDatabase('permissions', ['slug' => 'users.manage']);

        // Check Assignment (Editor -> content.create)
        // Need to find IDs first
        $db = \Config\Database::connect();
        $role = $db->table('roles')->where('slug', 'editor')->get()->getRow();
        $perm = $db->table('permissions')->where('slug', 'content.create')->get()->getRow();

        $this->seeInDatabase('role_permissions', [
            'role_id' => $role->id,
            'permission_id' => $perm->id
        ]);
    }
}
