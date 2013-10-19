<?php
use anlutro\Access\Models\Role;
use anlutro\Access\Models\User;
use anlutro\Access\Models\Permission;

class UserRoleTest extends TestCase
{
	public function testAttachUserToRole()
	{
		$user = User::create(['name' => 'user']);
		$role = Role::create(['name' => 'role']);
		$user->addRole($role);

		$this->assertTrue($user->roles->contains($role->getKey()));
	}

	public function testRemoveUserRole()
	{
		$user = User::create(['name' => 'user']);
		$role = Role::create(['name' => 'role']);
		$user->addRole($role);
		$user->removeRole($role);

		$this->assertFalse($user->roles->contains($role->getKey()));
	}
}
