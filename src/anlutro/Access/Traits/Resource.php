<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

namespace anlutro\Access\Traits;

use anlutro\Access\Models\Permission;
use anlutro\Access\Models\PermissionCollection;

/**
 * Trait that can be applied to resource models.
 */
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
	 * {@inheritdoc}
	 */
	public function mergedPermissions()
	{
		$merged = $this->permissions()
			->whereNull('permission_resource.resource_id')
			->get();

		return $merged->merge($this->permissions);
	}

	/**
	 * {@inheritdoc}
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
	 * {@inheritdoc}
	 */
	public function addPermissionTo($action, Permission $permission)
	{
		$this->permissions()->attach($permission, ['action' => $action]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function removePermissionTo($action, Permission $permission = null)
	{
		$query = $this->permissions()
			->newPivotStatement()
			->where('action', $action);

		if ($permission !== null)
			$query->where('permission_id', $permission->getKey());

		$query->delete();
	}

	/**
	 * {@inheritdoc}
	 */
	public function requiresPermissionTo($action, Permission $permission = null)
	{
		$query = $this->permissions()
			->newPivotStatement()
			->where('action', $action)
			->where('resource_type', get_class($this))
			->where(function($query) use($permission) {
				$query->where('resource_id', $this->getKey())
					->orWhereNull('resource_id');
			});

		if ($permission !== null) {
			$query->where('permission_id', $permission->getKey());
		}
		
		return $query->count() > 0;
	}

	/**
	 * {@inheritdoc}
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
	 * {@inheritdoc}
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

		$query->delete();
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setGlobalPermissionsTo($action, PermissionCollection $permissions)
	{
		static::removeGlobalPermissionTo($action);

		foreach ($permissions as $permission) {
			static::addGlobalPermissionTo($action, $permission);
		}
	}

	/**
	 * {@inheritdoc}
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
