<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

namespace anlutro\Access\Traits;

use anlutro\Access\Interfaces\SubjectInterface;
use anlutro\Access\Interfaces\ResourceInterface;
use anlutro\Access\Models\Permission;
use anlutro\Access\Models\PermissionCollection;
use anlutro\Access\Models\Role;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait that "extends" Subject, but made specifically for User models.
 */
trait UserSubject
{
	use Subject;

	/**
	 * Define the user->role relationship.
	 *
	 * @return BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany('anlutro\Access\Models\Role', 'user_role', 'user_id', 'role_id');
	}

	/**
	 * Define the user->permissions relationship.
	 *
	 * @return BelongsToMany
	 */
	public function permissions()
	{
		return $this->belongsToMany('anlutro\Access\Models\Permission', 'user_permission', 'user_id', 'permission_id')
			->withPivot('allow');
	}

	/**
	 * {@inheritdoc}
	 */
	public function mergedPermissions()
	{
		$merged = new PermissionCollection;

		$roles = $this->roles()->with('permissions')->get();

		foreach ($roles as $role) {
			$merged->merge($role->permissions);
		}

		$merged->merge($this->permissions);

		return $merged;
	}

	/**
	 * Add a role to the user.
	 *
	 * @param Role $role
	 */
	public function addRole(Role $role)
	{
		$this->roles()->attach($role);
	}

	/**
	 * Remove a role from the user.
	 *
	 * @param  Role   $role
	 */
	public function removeRole(Role $role)
	{
		$this->roles()->detach($role);
	}
}
