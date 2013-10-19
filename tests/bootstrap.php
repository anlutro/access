<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/TestCase.php';

foreach (glob(__DIR__ . '/../src/migrations/*.php') as $filename) {
	require_once $filename;
}

foreach (glob(__DIR__ . '/migrations/*.php') as $filename) {
	require_once $filename;
}

foreach (glob(__DIR__ . '/models/*.php') as $filename) {
	require_once $filename;
}

$capsule = new Illuminate\Database\Capsule\Manager;

$capsule->addConnection([
	'driver'    => 'sqlite',
	'database'  => ':memory:',
	'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

class_alias('Illuminate\Database\Capsule\Manager', 'DB');

$fakeapp = array();
$fakeapp['db'] = $capsule;
Illuminate\Support\Facades\Schema::setFacadeApplication($fakeapp);
