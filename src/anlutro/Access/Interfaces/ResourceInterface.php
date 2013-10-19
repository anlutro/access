<?php
namespace anlutro\Access\Interfaces;

use anlutro\Access\Models\PermissionCollection;
use anlutro\Access\Models\Permission;

interface ResourceInterface
{
	public function mergedPermissions();
	public function permissionsRequiredTo($action);
	public function addPermissionTo($action, Permission $permission);
	public function removePermissionTo($action, Permission $permission);
	public function requiresPermissionTo($action, Permission $permission);
	public static function globalPermissionsRequiredTo($action);
	public static function addGlobalPermissionTo($action, Permission $permission);
	public static function removeGlobalPermissionTo($action, Permission $permission = null);
	public static function setGlobalPermissionsto($action, PermissionCollection $permissions);
}
