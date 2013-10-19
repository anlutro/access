<?php
namespace anlutro\Access;

class Access
{
	public static function allowed($action, ResourceInterface $resource)
	{
		if (!Auth::check()) return false;
		return Auth::user()->hasAccessTo($action, $resource);
	}

	public static function denied($action, ResourceInterface $resource)
	{
		return !static::allowed($action, $resource);
	}
}
