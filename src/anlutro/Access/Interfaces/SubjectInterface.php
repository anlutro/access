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

interface SubjectInterface
{
	public function allowPermission(Permission $permission);
	public function denyPermission(Permission $permission);
	public function resetPermission(Permission $permission);
	public function hasAccessTo($action, ResourceInterface $resource);
}
