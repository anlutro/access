<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Config;

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
		$this->setUpSchemaFacade();

		foreach($this->migrations as $class) {
			$migration = new $class;
			$migration->up();
			$migration = null;
		}

		$this->tearDownFacades();

		Model::unguard();
		Config::shouldReceive('get')
			->with('auth.model', Mockery::any())
			->andReturn('TestUser');
	}

	public function tearDown()
	{
		$this->setUpSchemaFacade();

		foreach($this->migrations as $class) {
			$migration = new $class;
			$migration->down();
			$migration = null;
		}

		$this->tearDownFacades();
	}

	protected function setUpSchemaFacade()
	{
		// I'm sorry
		global $capsule;
		$fakeapp = array('db' => $capsule);
		Facade::setFacadeApplication($fakeapp);
	}

	protected function tearDownFacades()
	{
		Facade::setFacadeApplication(null);
		Facade::clearResolvedInstances();
		Mockery::close();
	}
}
