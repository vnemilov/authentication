<?php 

//import namespaces 
use Slim\Slim;
use Noodlehaus\Config;


//Start the session
session_cache_limiter(false);
session_start();

//displaying php errors on page if there are any
ini_set('display_errors', 'On');

//include root - 'C:\wamp\www\authentication'
define('INC_ROOT', dirname(__DIR__));

//load all dependencies from vendor
require INC_ROOT . '/vendor/autoload.php';

//$app serves as our entire application
$app = new Slim([
//reading the mode.php file
	'mode' => file_get_contents(INC_ROOT . '/mode.php')
	]);
//setting the mode of the application
$app->configureMode($app->config('mode'), function() use ($app){
	$app->config = Config::load(INC_ROOT . "/app/config/{$app->mode}.php");
});

//example of using the configs from the loaded mode
/*echo $app->config->get('db.driver');*/

require 'database.php';



?>