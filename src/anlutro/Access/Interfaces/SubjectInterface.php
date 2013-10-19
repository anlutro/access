<?php
namespace anlutro\Access\Interfaces;

use anlutro\Access\Models\Permission;

interface SubjectInterface
{
	public function allowPermission(Permission $permission);
	public function denyPermission(Permission $permission);
	public function resetPermission(Permission $permission);
	public function hasAccessTo($action, ResourceInterface $resource);
}
