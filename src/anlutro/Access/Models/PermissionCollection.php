<?php
namespace anlutro\Access\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Contracts\ArrayableInterface;

class PermissionCollection extends \Illuminate\Database\Eloquent\Collection
{
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
	 * Override the merge method to allow for some more advanced logic.
	 *
	 * @param  PermissionCollection $new
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
	}

	/**
	 * Overwrite the add method to prevent duplicates.
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
