<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

namespace anlutro\Access\Interfaces;

use anlutro\Access\Models\PermissionCollection;
use anlutro\Access\Models\Permission;

/**
 * Interface used by resources in the RBAC system. Resources are entities which
 * can require certain permissions for actions to be executed on them.
 */
interface ResourceInterface
{
	/**
	 * Returns the complete set of permission on the resource, where row-specific
	 * permissions override type-specific permissions.
	 *
	 * @return PermissionCollection
	 */
	public function mergedPermissions();

	/**
	 * Get the permissions required to execute a certain action on the resource.
	 *
	 * @param  string $action
	 *
	 * @return PermissionCollection
	 */
	public function permissionsRequiredTo($action);

	/**
	 * Add a permission required to do a certain action with this resource.
	 *
	 * @param string     $action
	 * @param Permission $permission
	 */
	public function addPermissionTo($action, Permission $permission);

	/**
	 * Remove a permission required to do a certain action with this resource.
	 *
	 * @param  string     $action
	 * @param  Permission $permission
	 */
	public function removePermissionTo($action, Permission $permission);

	/**
	 * Check if a permission is required to do an action on this resource.
	 *
	 * @param  string     $action     
	 * @param  Permission $permission 
	 */
	public function requiresPermissionTo($action, Permission $permission);

	/**
	 * Get the permission(s) required for an action on this resource type.
	 *
	 * @param  string $action
	 *
	 * @return PermissionCollection
	 */
	public static function globalPermissionsRequiredTo($action);

	/**
	 * Add a permission required to do an action on all resources of this type.
	 *
	 * @param  string     $action
	 * @param  Permission $permission
	 */
	public static function addGlobalPermissionTo($action, Permission $permission);

	/**
	 * Remove all permissions required to do an action on all resources of this
	 * type, or remove a specific permission.
	 *
	 * @param  string     $action     
	 * @param  Permission $permission  optional
	 */
	public static function removeGlobalPermissionTo($action, Permission $permission = null);

	/**
	 * Set the global permission(s) required for an action on a resource type.
	 *
	 * @param  string $action
	 * @param  PermissionCollection $permissions
	 */
	public static function setGlobalPermissionsTo($action, PermissionCollection $permissions);
}
