<?php
namespace anlutro\Access\Traits;

use anlutro\Access\Models\Permission;
use anlutro\Access\Models\PermissionCollection;

trait Resource
{
	/**
	 * Define the resource->permission relationship. Note that this will only
	 * get the permissions unique to the current object. Use mergedPermissions()
	 * instead to get the real permissions.
	 * 
	 * @see    mergedPermissions
	 *
	 * @return PermissionCollection
	 */
	public function permissions()
	{
		return $this->morphToMany('anlutro\Access\Models\Permission', 'resource', 'permission_resource')
			->withPivot('action');
	}

	/**
	 * Get the merged permissions for the resource.
	 *
	 * @return PermissionCollection
	 */
	public function mergedPermissions()
	{
		$merged = $this->permissions()
			->whereNull('permission_resource.resource_id')
			->get();

		return $merged->merge($this->permissions);
	}

	/**
	 * Get the permissions required to execute a certain action on the resource.
	 *
	 * @param  string $action
	 */
	public function permissionsRequiredTo($action)
	{
		$permissions = $this->permissions()
			->where('permission_resource.action', $action)
			->get();

		if ($permissions->isEmpty()) {
			return static::globalPermissionsRequiredTo($action);
		} else {
			return $permissions;
		}
	}

	/**
	 * Add a permission required to do a certain action with this resource.
	 *
	 * @param string     $action
	 * @param Permission $permission
	 */
	public function addPermissionTo($action, Permission $permission)
	{
		$this->permissions()->attach($permission, ['action' => $action]);
	}

	/**
	 * Remove a permission required to do a certain action with this resource.
	 *
	 * @param  string     $action
	 * @param  Permission $permission
	 *
	 * @return int
	 */
	public function removePermissionTo($action, Permission $permission = null)
	{
		$query = $this->permissions()
			->newPivotStatement()
			->where('action', $action);


		if ($permission !== null)
			$query->where('permission_id', $permission->getKey());

		return $query->delete();
	}

	/**
	 * Check if a permission is required to do an action on this resource.
	 *
	 * @param  string     $action     
	 * @param  Permission $permission 
	 */
	public function requiresPermissionTo($action, Permission $permission)
	{
		$permCount = $this->permissions()
			->newPivotStatement()
			->where('action', $action)
			->where('resource_type', get_class($this))
			->where(function($query) use($permission) {
				$query->where('resource_id', $permission->id)
					->orWhereNull('resource_id');
			})->count();

		return $permCount > 0;
	}

	/**
	 * Add a permission required to do an action on all resources of this type.
	 *
	 * @param string     $action     
	 * @param Permission $permission 
	 */
	public static function addGlobalPermissionTo($action, Permission $permission)
	{
		$instance = new static;
		$query = $instance->permissions()
			->attach($permission, [
				'action' => $action,
				'resource_id' => null,
			]);
	}

	/**
	 * Remove all permissions required to do an action on all resources of this
	 * type, or remove a specific permission.
	 *
	 * @param  string     $action     
	 * @param  Permission $permission  optional
	 */
	public static function removeGlobalPermissionTo($action, Permission $permission = null)
	{
		$instance = new static;
		$query = $instance->permissions()
			->newPivotStatement()
			->where('action', $action)
			->where('resource_type', get_called_class())
			->whereNull('resource_id');

		if ($permission) {
			$query->where('permission_id', $permission->getKey());
		}

		return $query->delete();
	}

	/**
	 * Set the global permission(s) required for an action on a resource type.
	 *
	 * @param string $action
	 * @param PermissionCollection $permissions
	 */
	public static function setGlobalPermissionsTo($action, PermissionCollection $permissions)
	{
		static::removeGlobalPermissionTo($action);

		foreach ($permissions as $permission) {
			static::addGlobalPermissionTo($action, $permission);
		}

		return true;
	}

	/**
	 * Get the permission(s) required for an action on this resource type.
	 *
	 * @param  string $action
	 *
	 * @return PermissionCollection
	 */
	public static function globalPermissionsRequiredTo($action)
	{
		$instance = new static;
		$query = $instance->permissions();
		$query->wheres = [];
		$query->where('action', $action)
			->where('resource_type', get_called_class())
			->whereNull('resource_id');
		return $query->get();
	}
}
