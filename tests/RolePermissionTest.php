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

class RolePermissionTest extends TestCase
{
	public function testAttachRolePermissionAllow()
	{
		$role = Role::create(['name' => 'role']);
		$perm = Permission::create(['name' => 'permission']);
		$role->allowPermission($perm);

		$this->assertTrue($role->permissions->contains($perm->getKey()));
		$this->assertTrue($perm->roles->contains($role->getKey()));
		$this->assertTrue($role->permissions->first()->allow);
	}

	public function testAttachRolePermissionDeny()
	{
		$role = Role::create(['name' => 'role']);
		$perm = Permission::create(['name' => 'permission']);
		$role->denyPermission($perm);

		$this->assertTrue($role->permissions->contains($perm->getKey()));
		$this->assertTrue($perm->roles->contains($role->getKey()));
		$this->assertFalse($role->permissions->first()->allow);
	}

	public function testResetPermission()
	{
		$role = Role::create(['name' => 'role']);
		$perm = Permission::create(['name' => 'permission']);
		$role->allowPermission($perm);
		$role->resetPermission($perm);

		$this->assertFalse($role->permissions->contains($perm->getKey()));
		$this->assertFalse($perm->roles->contains($role->getKey()));
	}
}
