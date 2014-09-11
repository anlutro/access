<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

namespace anlutro\Access\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Contracts\ArrayableInterface;

/**
 * Custom collection object for Permission objects for some extra control and
 * more efficient merging.
 */
class PermissionCollection extends \Illuminate\Database\Eloquent\Collection
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct(array $models = array())
	{
		if ($models) {
			// get the model keys
			$keys = array_map(function($m) { return $m->getKey(); }, $models);

			// apply the model keys
			$mappedItems = array_combine($keys, array_values($models));

			// store the items
			$this->items = $mappedItems;
		} else {
			$this->items = $models;
		}
	}

	/**
	 * Custom merge function for custom functionality.
	 * 
	 * @param  Collection $new
	 * 
	 * @return $this
	 */
	public function merge($new)
	{
		if (!$new instanceof PermissionsCollection) {
			if ($new instanceof Collection) {
			    $new = $new->all();
			} elseif ($new instanceof ArrayableInterface) {
			    $new = $new->toArray();
			}
		}

		foreach ($new as $permission) {
			// check if it already exists
			$match = $this->find( $permission->getKey() );

			if (!$match) {
				// add if not found
				$this->add($permission);
			} elseif ($match->allow && !$permission->allow) {
				// deny gets precedence over allow
				$this->put($match->getKey(), $permission);
			}

			// else do nothing.
		}

		return $this;
	}

	/**
	 * Override the add method to prevent duplicates.
	 * 
	 * @param  $model mixed
	 */
	public function add($model)
	{
		$key = $model->getKey();

		if (isset($this->items[$key])) {
			throw new \RuntimeException('Duplicate models');
		}

		$this->put($model->getKey(), $model);
	}
}
