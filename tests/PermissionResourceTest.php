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

class PermissionResourceTest extends TestCase
{
	public function testAttachResourceGlobalPermission()
	{
		$res = TestResource::create(['name' => 'resource']);
		$perm = Permission::create(['name' => 'permission']);

		$this->assertFalse($res->requiresPermissionTo('show', $perm));
		TestResource::addGlobalPermissionTo('show', $perm);
		$this->assertTrue($res->requiresPermissionTo('show', $perm));
	}

	public function testAttachResourceLocalPermission()
	{
		$res = TestResource::create(['name' => 'resource']);
		$perm = Permission::create(['name' => 'permission']);

		$res->addPermissionTo('show', $perm);
		$this->assertTrue($res->requiresPermissionTo('show', $perm));
	}

	public function testRemoveLocalPermission()
	{
		$res = TestResource::create(['name' => 'resource']);
		$perm = Permission::create(['name' => 'permission']);

		$res->addPermissionTo('show', $perm);
		$res->removePermissionTo('show', $perm);
		$this->assertFalse($res->requiresPermissionTo('show', $perm));
	}

	public function testRemoveGlobalPermission()
	{
		$res = TestResource::create(['name' => 'resource']);
		$perm = Permission::create(['name' => 'permission']);

		TestResource::addGlobalPermissionTo('show', $perm);
		TestResource::removeGlobalPermissionTo('show', $perm);
		$this->assertFalse($res->requiresPermissionTo('show', $perm));
	}

	public function testRoleHasAccessToResourceWithPermission()
	{
		$res = TestResource::create(['name' => 'resource']);
		$role = Role::create(['name' => 'role']);
		$perm = Permission::create(['name' => 'permission']);
		
		$res->addPermissionTo('show', $perm);
		$this->assertFalse($role->hasAccessTo('show', $res));
		
		$role->allowPermission($perm);
		$this->assertTrue($role->hasAccessTo('show', $res));
	}

	public function testRoleHasNoAccessToResourceWithDeniedPermission()
	{
		$res = TestResource::create(['name' => 'resource']);
		$role = Role::create(['name' => 'role']);
		$perm = Permission::create(['name' => 'permission']);
		
		$res->addPermissionTo('show', $perm);
		$role->denyPermission($perm);

		$this->assertfalse($role->hasAccessTo('show', $res));
	}

	public function testLocalPermissionAddsToGlobal()
	{
		$res1 = TestResource::create(['name' => 'resource 1']);
		$res2 = TestResource::create(['name' => 'resource 2']);
		$role = Role::create(['name' => 'role']);
		$perm1 = Permission::create(['name' => 'permission 1']);
		$perm2 = Permission::create(['name' => 'permission 2']);

		TestResource::addGlobalPermissionTo('show', $perm1);
		$res2->addPermissionTo('show', $perm2);
		$role->allowPermission($perm1);

		$this->assertTrue($role->hasAccessTo('show', $res1));
		$this->assertFalse($role->hasAccessTo('show', $res2));

		$role->allowPermission($perm2);
		$this->assertTrue($role->hasAccessTo('show', $res2));
	}

	public function testMultipleActionPermissions()
	{
		$res = TestResource::create(['name' => 'resource']);
		$perm1 = Permission::create(['name' => 'permission 1']);
		$perm2 = Permission::create(['name' => 'permission 2']);

		$res->addPermissionTo('show', $perm1);
		$res->addPermissionTo('edit', $perm1);
		$res->addPermissionTo('edit', $perm2);

		$this->assertTrue($res->requiresPermissionTo('show', $perm1));
		$this->assertFalse($res->requiresPermissionTo('show', $perm2));
		$this->assertTrue($res->requiresPermissionTo('edit', $perm1));
		$this->assertTrue($res->requiresPermissionTo('edit', $perm1));
	}
}
