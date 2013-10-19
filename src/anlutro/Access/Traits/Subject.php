<?php
namespace anlutro\Access\Traits;

use anlutro\Access\Interfaces\SubjectInterface;
use anlutro\Access\Interfaces\ResourceInterface;
use anlutro\Access\Models\Permission;
use Illuminate\Database\Eloquent\Model;

trait Subject
{
	/**
	 * Add a permission to the object with allow = true.
	 *
	 * @param  Permission $permission
	 *
	 * @return void
	 */
	public function allowPermission(Permission $permission)
	{
		$this->permissions()
			->attach($permission, ['allow' => true]);

		// we need to unset the relationship "cache" to make sure the permissions
		// are updated properly.
		// @todo figure out a way to just add $permission to the colleciton
		if (isset($this->permissions))
			unset($this->permissions);
	}

	/**
	 * Add a permission to the object with allow = false.
	 *
	 * @param  Permission $permission
	 *
	 * @return void
	 */
	public function denyPermission(Permission $permission)
	{
		$this->permissions()
			->attach($permission, ['allow' => false]);

		if (isset($this->permissions))
			$this->permissions->filter(function($item) use($permission) {
				return ($item->getKey() != $permission->getKey());
			});
	}

	/**
	 * Reset permissions. Deletes all pivot table entries for this object.
	 *
	 * @param  Permission $permission
	 *
	 * @return void
	 */
	public function resetPermission(Permission $permission)
	{
		$this->permissions()
			->detach($permission);
	}

	/**
	 * Get a permission from the subject.
	 *
	 * @param  Permission $permission [description]
	 *
	 * @return boolean                [description]
	 */
	public function getPermission(Permission $permission)
	{
		return $this->mergedPermissions()
			->find($permission->getKey());
	}

	/**
	 * Check if the user/role has access to an action on a resource.
	 *
	 * @param  string   $action
	 * @param  ResourceInterface $resource
	 *
	 * @return boolean
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
