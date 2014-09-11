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
use Illuminate\Database\Eloquent\Model;
use anlutro\Access\Interfaces\SubjectInterface;
use anlutro\Access\Traits\Subject;

/**
 * Role model
 * 
 * Roles are subjects with a many-to-many relationship with users. They have
 * permissions which have less priority than the user-specific permissions.
 */
class Role extends Model implements SubjectInterface
{
	use Subject;

	/**
	 * {@inheritdoc}
	 */
	public $timestamps = false;

	/**
	 * Define the role->users relationship.
	 *
	 * @return BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany(Config::get('auth.model', 'anlutro\Access\Models\User'),
			'user_role', 'role_id', 'user_id');
	}

	/**
	 * Define the role->permissions relationship.
	 *
	 * @return BelongsToMany
	 */
	public function permissions()
	{
		return $this->belongsToMany('anlutro\Access\Models\Permission',
			'role_permission', 'role_id', 'permission_id')
			->withPivot('allow');
	}

	/**
	 * {@inheritdoc}
	 */
	public function mergedPermissions()
	{
		return $this->permissions;
	}
}
