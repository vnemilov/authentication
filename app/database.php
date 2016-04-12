<?php 

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
//Adding database connection using the configuration file
$capsule->addConnection([
	'driver' => $app->config->get('db.driver'),
	'host' => $app->config->get('db.host'),
	'database' => $app->config->get('db.name'),
	'username' => $app->config->get('db.username'),
	'password' => $app->config->get('db.password'),
	'charset' => $app->config->get('db.charset'),
	'colaltion' => $app->config->get('db.collation'),
	'prefix' => $app->config->get('db.prefix')
	]);

$capsule->bootEloquent();



?>