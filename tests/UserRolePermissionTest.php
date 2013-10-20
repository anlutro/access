<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

use anlutro\Access\Models\Role;
use anlutro\Access\Models\Permission;

class UserRolePermissionTest extends TestCase
{
	public function testUserRolePermissionAllow()
	{
		$user = TestUser::create(['name' => 'user']);
		$role = Role::create(['name' => 'role']);
		$perm = Permission::create(['name' => 'permission']);
		
		$this->assertNull($user->getPermission($perm));

		$role->allowPermission($perm);
		$user->addRole($role);

		$perm = $user->getPermission($perm);
		$this->assertNotNull($perm);
		$this->assertTrue($perm->allow);
	}

	public function testUserRolePermissionDeny()
	{
		$user = TestUser::create(['name' => 'user']);
		$role = Role::create(['name' => 'role']);
		$perm = Permission::create(['name' => 'permission']);
		
		$this->assertNull($user->getPermission($perm));

		$role->denyPermission($perm);
		$user->addRole($role);

		$perm = $user->getPermission($perm);
		$this->assertNotNull($perm);
		$this->assertFalse($perm->allow);
	}

	public function testUserRoleDenyPermissionGetsPrecedence()
	{
		$user = TestUser::create(['name' => 'user']);
		$role1 = Role::create(['name' => 'role 1']);
		$role2 = Role::create(['name' => 'role 2']);
		$perm = Permission::create(['name' => 'permission']);

		$user->addRole($role1);
		$user->addRole($role2);
		$role1->allowPermission($perm);
		$role2->denyPermission($perm);

		$perm = $user->getPermission($perm);
		$this->assertNotNull($perm);
		$this->assertFalse($perm->allow);
	}

	public function testUserPermissionComesBeforeRolePermission()
	{
		$user = TestUser::create(['name' => 'user']);
		$role = Role::create(['name' => 'role']);
		$perm = Permission::create(['name' => 'permission']);

		$user->addRole($role);
		$role->allowPermission($perm);
		$user->denyPermission($perm);

		$perm = $user->getPermission($perm);
		$this->assertNotNull($perm);
		$this->assertFalse($perm->allow);
	}
}
