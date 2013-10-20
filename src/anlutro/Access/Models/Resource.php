<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

namespace anlutro\Access\Models;

use anlutro\Access\Interfaces\ResourceInterface;
use anlutro\Access\Traits\Resource as ResourceTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Abstract class that can easily be extended to have a resource model.
 */
abstract class Resource extends Model implements ResourceInterface
{
	use ResourceTrait;
}
