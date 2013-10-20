<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

namespace anlutro\Access\Models;

use anlutro\Access\Interfaces\SubjectInterface;
use anlutro\Access\Traits\UserSubject;
use Illuminate\Database\Eloquent\Model;

/**
 * Abstract class that can easily be extended to have an RBAC-compliant User
 * model that can be customized.
 */
class User extends Model implements SubjectInterface
{
	use UserSubject;
}
