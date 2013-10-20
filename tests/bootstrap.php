<?php
/**
 * Access - Laravel 4 RBAC
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/access
 */

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

$capsule->setAsGlobal();
$capsule->bootEloquent();
