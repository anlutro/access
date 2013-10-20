<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

namespace anlutro\Access;

use anlutro\Access\Interfaces\ResourceInterface;
use Illuminate\Support\Facades\Auth;

class Access
{
	public static function allowed($action, ResourceInterface $resource)
	{
		if (Auth::check()) {
			return Auth::user()->hasAccessTo($action, $resource);
		} else {
			return $resource->permissionsRequiredTo($action)->count() <= 0;
		}
	}

	public static function denied($action, ResourceInterface $resource)
	{
		return !static::allowed($action, $resource);
	}
}
