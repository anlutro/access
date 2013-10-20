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

class UserRoleTest extends TestCase
{
	public function testAttachUserToRole()
	{
		$user = TestUser::create(['name' => 'user']);
		$role = Role::create(['name' => 'role']);
		$user->addRole($role);

		$this->assertTrue($user->roles->contains($role->getKey()));

		$this->assertTrue($role->users->contains($user->getKey()));
	}

	public function testRemoveUserRole()
	{
		$user = TestUser::create(['name' => 'user']);
		$role = Role::create(['name' => 'role']);
		$user->addRole($role);
		$user->removeRole($role);

		$this->assertFalse($user->roles->contains($role->getKey()));
		$this->assertFalse($role->users->contains($user->getKey()));
	}
}
