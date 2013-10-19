<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

namespace anlutro\Access\Models;

use Illuminate\Support\Facades\Config;

class Role extends AbstractSubject
{
	public $timestamps = false;

	public function users()
	{
		return $this->belongsToMany(Config::get('auth.model', 'anlutro\Access\Models\User'), 'user_role');
	}

	public function permissions()
	{
		return $this->belongsToMany('anlutro\Access\Models\Permission', 'role_permission')
			->withPivot('allow');
	}

	public function mergedPermissions()
	{
		return $this->permissions;
	}
}