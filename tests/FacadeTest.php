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
use anlutro\Access\Access;
use Illuminate\Support\Facades\Auth;

class FacadeTest extends TestCase
{
	public function testAccessDeniedWhenUserDenied()
	{
		$user = TestUser::create(['name' => 'user']);
		$perm = Permission::create(['name' => 'permission']);
		$res = TestResource::create(['name' => 'resource']);

		$res->addPermissionTo('show', $perm);
		$user->denyPermission($perm);
		Auth::shouldReceive('check')->andReturn(true);
		Auth::shouldReceive('user')->andReturn($user);

		$this->assertFalse($user->hasAccessTo('show', $res),
			'Asserting that $user does not have access to action on resource');
		$this->assertFalse(Access::allowed('show', $res),
			'Asserting that Access::allowed == false');
		$this->assertTrue(Access::denied('show', $res),
			'Asserting that Access::denied == true');
	}

	public function testAccessAllowedWhenUserAllowed()
	{
		$user = TestUser::create(['name' => 'user']);
		$perm = Permission::create(['name' => 'permission']);
		$res = TestResource::create(['name' => 'resource']);

		$res->addPermissionTo('show', $perm);
		$user->allowPermission($perm);
		Auth::shouldReceive('check')->andReturn(true);
		Auth::shouldReceive('user')->andReturn($user);

		$this->assertTrue($user->hasAccessTo('show', $res),
			'Asserting that $user has access to action on resource');
		$this->assertTrue(Access::allowed('show', $res),
			'Asserting that Access::allowed == true');
		$this->assertFalse(Access::denied('show', $res),
			'Asserting that Access::denied == false');
	}

	public function testAccessAllowedWhenLoggedOutButNoPermissionsRequired()
	{
		$res = TestResource::create(['name' => 'resource']);

		Auth::shouldReceive('check')->andReturn(false);

		$this->assertTrue(Access::allowed('show', $res),
			'Asserting that Access::allowed == true');
		$this->assertFalse(Access::denied('show', $res),
			'Asserting that Access::denied == false');
	}

	public function testAccessDeniedWhenLoggedOutAndPermissionsRequired()
	{
		$res = TestResource::create(['name' => 'resource']);
		$perm = Permission::create(['name' => 'permission']);
		$res->addPermissionTo('show', $perm);
		
		Auth::shouldReceive('check')->andReturn(false);

		$this->assertFalse(Access::allowed('show', $res),
			'Asserting that Access::allowed == false');
		$this->assertTrue(Access::denied('show', $res),
			'Asserting that Access::denied == true');
	}
}
