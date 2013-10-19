<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

use anlutro\Access\Models\Role;
use anlutro\Access\Models\User;
use anlutro\Access\Models\Permission;

class UserPermissionTest extends TestCase
{
	public function testAttachUserPermissionAllow()
	{
		$user = User::create(['name' => 'user']);
		$perm = Permission::create(['name' => 'permission']);
		$user->allowPermission($perm);

		$this->assertTrue($user->permissions->contains($perm->getKey()));
		$this->assertTrue($user->permissions->first()->allow);
	}

	public function testAttachUserPermissionDeny()
	{
		$user = User::create(['name' => 'user']);
		$perm = Permission::create(['name' => 'permission']);
		$user->denyPermission($perm);

		$this->assertTrue($user->permissions->contains($perm->getKey()));
		$this->assertFalse($user->permissions->first()->allow);
	}
}
