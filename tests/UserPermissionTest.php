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

class UserPermissionTest extends TestCase
{
	public function testAttachUserPermissionAllow()
	{
		$user = TestUser::create(['name' => 'user']);
		$perm = Permission::create(['name' => 'permission']);
		$user->allowPermission($perm);

		$this->assertTrue($user->permissions->contains($perm->getKey()));
		$this->assertTrue($user->permissions->first()->allow);
	}

	public function testAttachUserPermissionDeny()
	{
		$user = TestUser::create(['name' => 'user']);
		$perm = Permission::create(['name' => 'permission']);
		$user->denyPermission($perm);

		$this->assertTrue($user->permissions->contains($perm->getKey()));
		$this->assertFalse($user->permissions->first()->allow);
	}

	public function testResetPermission()
	{
		$user = TestUser::create(['name' => 'user']);
		$perm = Permission::create(['name' => 'permission']);
		$user->allowPermission($perm);
		$user->resetPermission($perm);

		$this->assertFalse($user->permissions->contains($perm->getKey()));
		$this->assertFalse($perm->users->contains($user->getKey()));
	}
}
