<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

namespace anlutro\Access\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * Permission model
 * 
 * Permissions are attached to users and roles to define who gets access where.
 * User/Role-Permissions have a boolean flag on the pivot table named "allow"
 * which tells if said permission should be allowed or denied.
 * 
 * Permissions are also attached to resources, to be compared to the permissions
 * on a User or Role. These permissions have an "action" field on the pivot
 * table which lets the system know which action the permission is for.
 * 
 * Examples:
 * 
 * $user = User::first();
 * foreach ($user->permissions as $permission)
 *   echo $permission->name . '(' . $permission->allow . ')';
 * 
 * $resource = Resource::first();
 * foreach ($resource->permissions as $permission)
 *   echo $permission->name . '(' . $permission->action . ')';
 */
class Permission extends Model
{
	/**
	 * {@inheritdoc}
	 */
	public $timestamps = false;

	/**
	 * The users who have a relation to this permission. Keep in mind that this
	 * includes users where the permission is denied as well as users where the
	 * permission is allowed.
	 *
	 * @return BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany(Config::get('auth.model', 'anlutro\Access\Models\User'),
			'user_permission', 'permission_id', 'user_id');
	}

	/**
	 * The roles attached to this permission. Keep in mind that this includes
	 * roles where the permission is denied as well as roles where the
	 * permission is allowed.
	 *
	 * @return BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany('anlutro\Access\Models\Role',
			'role_permission', 'permission_id', 'role_id');
	}

	/**
	 * Allow easy access to a $userOrRole->permission->allow. Won't work on
	 * permissions that haven't been derived from a user or role.
	 *
	 * @return boolean
	 */
	public function getAllowAttribute()
	{
		if (!isset($this->pivot) || !isset($this->pivot->allow)) {
			throw new \RuntimeException('"allow" attribute only available on user/role permissions');
		}

		return (boolean) $this->pivot->allow;
	}

	/**
	 * Allow easy access to a $resource->permission->action. Won't work on
	 * permissions that haven't been derived from a resource.
	 *
	 * @return string
	 */
	public function getActionAttribute()
	{
		if (!isset($this->pivot) || !isset($this->pivot->action)) {
			throw new \RuntimeException('"action" attribute only available on resource permissions');
		}

		return $this->pivot->action;
	}

	/**
	 * Overwrite the newCollection function so that all collections of
	 * Permission objects are PermissionCollection objects.
	 *
	 * @param  array  $models
	 *
	 * @return PermissionCollection
	 */
	public function newCollection(array $models = array())
	{
		return new PermissionCollection($models);
	}
}
