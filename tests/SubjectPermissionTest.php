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
	protected function attachSubjectPermissionAllow($subject, $perm)
	{
		$subject->allowPermission($perm);
		$this->assertTrue($subject->permissions->contains($perm->getKey()));
		$this->assertTrue($subject->permissions->first()->allow);

		if ($subject instanceof anlutro\Access\Models\User)
			$rel = 'users';
		elseif ($subject instanceof anlutro\Access\Models\Role)
			$rel = 'roles';
		$this->assertTrue($perm->$rel->contains($subject->getKey()));
	}

	public function testAttachUserPermissionAllow()
	{
		$user = TestUser::create(['name' => 'user']);
		$perm = Permission::create(['name' => 'permission']);
		$this->attachSubjectPermissionAllow($user, $perm);
	}

	public function testAttachRolePermissionAllow()
	{
		$role = Role::create(['name' => 'role']);
		$perm = Permission::create(['name' => 'permission']);
		$this->attachSubjectPermissionAllow($role, $perm);
	}

	protected function attachSubjectPermissionDeny($subject, $perm)
	{
		$subject->denyPermission($perm);
		$this->assertTrue($subject->permissions->contains($perm->getKey()));
		$this->assertFalse($subject->permissions->first()->allow);

		if ($subject instanceof anlutro\Access\Models\User)
			$rel = 'users';
		elseif ($subject instanceof anlutro\Access\Models\Role)
			$rel = 'roles';
		$this->assertTrue($perm->$rel->contains($subject->getKey()));
	}

	public function testAttachUserPermissionDeny()
	{
		$user = TestUser::create(['name' => 'user']);
		$perm = Permission::create(['name' => 'permission']);
		$this->attachSubjectPermissionDeny($user, $perm);
	}

	public function testAttachRolePermissionDeny()
	{
		$role = Role::create(['name' => 'role']);
		$perm = Permission::create(['name' => 'permission']);
		$this->attachSubjectPermissionAllow($role, $perm);
	}

	protected function resetSubjectPermission($subject, $perm)
	{
		$subject->allowPermission($perm);
		$subject->resetPermission($perm);
		$this->assertFalse($subject->permissions->contains($perm->getKey()));

		if ($subject instanceof anlutro\Access\Models\User)
			$rel = 'users';
		elseif ($subject instanceof anlutro\Access\Models\Role)
			$rel = 'roles';
		$this->assertFalse($perm->$rel->contains($subject->getKey()));
	}

	public function testResetUserPermission()
	{
		$user = TestUser::create(['name' => 'user']);
		$perm = Permission::create(['name' => 'permission']);
		$this->resetSubjectPermission($user, $perm);
	}

	public function testResetRolePermission()
	{
		$role = Role::create(['name' => 'role']);
		$perm = Permission::create(['name' => 'permission']);
		$this->resetSubjectPermission($role, $perm);
	}

	/**
	 * For some reason using this function causes an infinite loop. Keeping it
	 * in case I can figure out what the cause is.
	 */
	protected function guessRelationship($subject)
	{
		if ($subject instanceof anlutro\Access\Models\User)
			$rel = 'users';
		elseif ($subject instanceof anlutro\Access\Models\Role)
			$rel = 'roles';
	}
}
