<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AccessCreateRolePermissionPivot extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('role_permission', function($t) {
			$t->integer('role_id')
				->unsigned();
			$t->integer('permission_id')
				->unsigned();
			$t->boolean('allow')
				->default(true);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('role_permission');
	}

}