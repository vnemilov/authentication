<?php 

//import namespaces 
use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

use Noodlehaus\Config;

//include the User model which we created
use Codecourse\User\User;
use Codecourse\Mail\Mailer;
use Codecourse\Helpers\Hash;
use Codecourse\Validation\Validator;


use Codecourse\Middleware\BeforeMiddleware;

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
	'mode' => file_get_contents(INC_ROOT . '/mode.php'),
	'view' => new Twig(),
	'templates.path' => INC_ROOT . '/app/views'
	]);

$app->add(new BeforeMiddleware);
//setting the mode of the application
$app->configureMode($app->config('mode'), function() use ($app){
	$app->config = Config::load(INC_ROOT . "/app/config/{$app->mode}.php");
});

//example of using the configs from the loaded mode
/*echo $app->config->get('db.driver');*/

require 'database.php';
require 'routes.php';

$app->auth = false;

//set our user model in our container it's accessed by $app->user
$app->container->set('user', function(){
	return new User;
});

$app->container->singleton('hash', function() use ($app){
	return new Hash($app->config);
});

$app->container->singleton('validation', function() use ($app){
	return new Validator($app->user);
});

$app->container->singleton('mail', function() use ($app){
	$mailer = new PHPMailer;

	$mailer->isSMTP();
	$mailer->Host = $app->config->get('mail.host');
	$mailer->SMTPAuth = $app->config->get('mail.smtp_auth');
	$mailer->SMTPSecure = $app->config->get('mail.smtp_secure');
	$mailer->Port = $app->config->get('mail.port');
	$mailer->Username = $app->config->get('mail.username');
	$mailer->Password = $app->config->get('mail.password');
$mailer->Mailer = "smtp";
	
	$mailer->isHTML($app->config->get('mail.html'));

	return new Mailer($app->view, $mailer);
});



$view = $app->view();

$view->parserOptions = [
'debug'=> $app->config->get('twig.debug')
];

$view->parserExtensions = [
new TwigExtension
];


// testing the hashing
/*$password = 'ilovecats';
$hash = '$2y$10$HUbuWaiSguV2B9dHCnrl0uQAUhtqVi7agZBh3sicaOzEFEcruBPIW';
var_dump($app->hash->passwordCheck($password, $hash));*/