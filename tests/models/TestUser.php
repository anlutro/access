<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

use anlutro\Access\Models\User;

class TestUser extends User
{
	protected $table = 'test_users';

	public $timestamps = false;
}
