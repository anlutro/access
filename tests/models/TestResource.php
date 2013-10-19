<?php
use anlutro\Access\Models\Resource;

class TestResource extends Resource
{
	protected $table = 'test_resource';

	public $timestamps = false;
}