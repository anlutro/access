<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

namespace anlutro\Access\Interfaces;

use anlutro\Access\Models\Permission;

/**
 * Interface used by subjects in the RBAC system. A subject is an entity that
 * can be allowed or denied access to certain permissions, for example users
 * and roles.
 */
interface SubjectInterface
{
	/**
	 * Allow a certain permission for this subject.
	 *
	 * @param  Permission $permission
	 */
	public function allowPermission(Permission $permission);

	/**
	 * Deny a certain permission for this subject.
	 *
	 * @param  Permission $permission
	 */
	public function denyPermission(Permission $permission);

	/**
	 * Reset the permission for a subject, deleting its pivot table entry.
	 *
	 * @param  Permission $permission
	 */
	public function resetPermission(Permission $permission);

	/**
	 * Check if the subject has access to an action on a resource.
	 *
	 * @param  string            $action
	 * @param  ResourceInterface $resource
	 *
	 * @return boolean
	 */
	public function hasAccessTo($action, ResourceInterface $resource);
}
