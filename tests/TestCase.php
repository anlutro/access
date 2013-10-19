<?php
use Illuminate\Database\Eloquent\Model;

class TestCase extends PHPUnit_Framework_TestCase
{
	private $migrations = [
		'CreateRoleTable',
		'CreatePermissionTable',
		'CreateUserRolePivot',
		'CreateUserPermissionPivot',
		'CreateRolePermissionPivot',
		'CreatePermissionResourcePivot',
		'CreateUserTable',
		'CreateTestResourceTable',
	];

	public function setUp()
	{
		foreach($this->migrations as $class) {
			$migration = new $class;
			$migration->up();
			$migration = null;
		}

		Model::unguard();
	}

	public function tearDown()
	{
		foreach($this->migrations as $class) {
			$migration = new $class;
			$migration->down();
			$migration = null;
		}
	}
}
