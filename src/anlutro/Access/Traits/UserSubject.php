<?php
namespace anlutro\Access\Traits;

use anlutro\Access\Interfaces\SubjectInterface;
use anlutro\Access\Interfaces\ResourceInterface;
use anlutro\Access\Models\Permission;
use anlutro\Access\Models\PermissionCollection;
use anlutro\Access\Models\Role;
use Illuminate\Database\Eloquent\Model;

trait UserSubject
{
	use Subject;

	public function roles()
	{
		return $this->belongsToMany('anlutro\Access\Models\Role', 'user_role');
	}

	public function permissions()
	{
		return $this->belongsToMany('anlutro\Access\Models\Permission', 'user_permission')
			->withPivot('allow');
	}

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

	public function addRole(Role $role)
	{
		$this->roles()->attach($role);
	}

	public function removeRole(Role $role)
	{
		$this->roles()->detach($role);
	}
}
