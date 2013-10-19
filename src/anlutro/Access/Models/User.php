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

class User extends Model implements SubjectInterface
{
	public $timestamps = false;

	use UserSubject;
}
