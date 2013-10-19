<?php
use anlutro\Access\Models\Role;
use anlutro\Access\Models\User;
use anlutro\Access\Models\Permission;

class RolePermissionTest extends TestCase
{
	public function testAttachRolePermissionAllow()
	{
		$role = Role::create(['name' => 'role']);
		$perm = Permission::create(['name' => 'permission']);
		$role->allowPermission($perm);

		$this->assertTrue($role->permissions->contains($perm->getKey()));
		$this->assertTrue($role->permissions->first()->allow);
	}

	public function testAttachRolePermissionDeny()
	{
		$role = Role::create(['name' => 'role']);
		$perm = Permission::create(['name' => 'permission']);
		$role->denyPermission($perm);

		$this->assertTrue($role->permissions->contains($perm->getKey()));
		$this->assertFalse($role->permissions->first()->allow);
	}
}
