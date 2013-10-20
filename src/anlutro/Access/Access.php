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

/**
 * This facade class has some convenience methods for common actions related to
 * checking whether the currently logged in user has access to a certain action
 * on a given resource.
 */
class Access
{
	/**
	 * Check if the logged in user is allowed a certain action on a given
	 * resource. Example: if (Access:allowed('edit', $article)) $article->update();
	 *
	 * @param  string            $action
	 * @param  ResourceInterface $resource
	 *
	 * @return bool
	 */
	public static function allowed($action, ResourceInterface $resource)
	{
		if (Auth::check()) {
			return Auth::user()->hasAccessTo($action, $resource);
		} else {
			return $resource->permissionsRequiredTo($action)->count() <= 0;
		}
	}

	/**
	 * Opposite of Access::allowed - returns true if access is denied. Useful
	 * for returning early from a function, for example:
	 * if (Access::denied('edit', $article)) return redirect();
	 *
	 * @param  string            $action
	 * @param  ResourceInterface $resource
	 *
	 * @return bool
	 */
	public static function denied($action, ResourceInterface $resource)
	{
		return !static::allowed($action, $resource);
	}
}
