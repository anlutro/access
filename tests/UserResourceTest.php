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

class UserResourceTest extends TestCase
{
	protected function createFakes()
	{
		return [
			User::create(['name' => 'user']),
			Role::create(['name' => 'role']),
			Permission::create(['name' => 'permission']),
			TestResource::create(['name' => 'resource']),
		];
	}

	public function testUserWithRoleHasAccessToResource()
	{
		list($user, $role, $perm, $res) = $this->createFakes();

		$user->addRole($role);
		$role->allowPermission($perm);
		$res->addPermissionTo('show', $perm);

		$this->assertTrue($user->hasAccessTo('show', $res));
	}

	public function testUserPermissionOverwritesRolePermission()
	{
		list($user, $role, $perm, $res) = $this->createFakes();

		$user->addRole($role);
		$role->allowPermission($perm);
		$user->denyPermission($perm);
		$res->addPermissionTo('show', $perm);

		$this->assertFalse($user->hasAccessTo('show', $res));
	}
}
