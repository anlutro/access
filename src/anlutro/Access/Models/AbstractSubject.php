<?php
namespace anlutro\Access\Models;

use anlutro\Access\Interfaces\SubjectInterface;
use anlutro\Access\Interfaces\ResourceInterface;
use anlutro\Access\Traits\Subject;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractSubject extends Model implements SubjectInterface
{
	use Subject;
}
