<?php
use anlutro\Access\Models\Role;
use anlutro\Access\Models\User;
use anlutro\Access\Models\Permission;

class UserRolePermissionTest extends TestCase
{
	public function testUserRolePermissionAllow()
	{
		$user = User::create(['name' => 'user']);
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
		$user = User::create(['name' => 'user']);
		$role = Role::create(['name' => 'role']);
		$perm = Permission::create(['name' => 'permission']);
		
		$this->assertNull($user->getPermission($perm));

		$role->denyPermission($perm);
		$user->addRole($role);

		$perm = $user->getPermission($perm);
		$this->assertNotNull($perm);
		$this->assertFalse($perm->allow);
	}
}
