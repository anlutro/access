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
use Illuminate\Database\Eloquent\Model;

/**
 * Trait that can be applied to subject models - users, roles etc.
 */
trait Subject
{
	/**
	 * {@inheritdoc}
	 */
	public function allowPermission(Permission $permission)
	{
		$existing = $this->permissions->find($permission->getKey());

		if ($existing) {
			$existing->pivot->allow = true;
			$existing->pivot->save();
		} else {
			$this->permissions()
				->attach($permission, ['allow' => true]);
			unset($this->permissions);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function denyPermission(Permission $permission)
	{
		$existing = $this->permissions->find($permission->getKey());

		if ($existing) {
			$existing->pivot->allow = false;
			$existing->pivot->save();
		} else {
			$this->permissions()
				->attach($permission, ['allow' => false]);
			unset($this->permissions);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function resetPermission(Permission $permission)
	{
		$this->permissions()
			->detach($permission);
	}

	/**
	 * Get a specific permission from the subject.
	 * 
	 * @param  Permission $permission
	 * 
	 * @return Permission
	 */
	public function getPermission(Permission $permission)
	{
		return $this->mergedPermissions()
			->find($permission->getKey());
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasAccessTo($action, ResourceInterface $resource)
	{
		$resourcePermissions = $resource->permissionsRequiredTo($action);
		$permissions = $this->mergedPermissions();

		// loop through the resource's permissions, comparing them to the
		// current user/role's permissions
		foreach ($resourcePermissions as $required) {
			if ($match = $permissions->find($required->getKey())) {
				// if a match is found and allow == false (deny access),
				// return false. otherwise, just continue
				if ($match->allow == false) {
					return false;
				}
			} else {
				// required permission on the resource was not found in the
				// user/role's permissions, so return false
				return false;
			}
		}

		// we've looped through all the required permissions and not found
		// anything indicating access should be denied, so return true
		return true;
	}
}
