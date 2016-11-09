<?php

// show all errors
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


// require stuff
if (!session_id()) {
	@session_start();
}
require_once 'vendor/autoload.php';


// constants & settings
define('BASE_URL', 'http://localhost:8888/blog');
define('APP_PATH', realpath(__DIR__ . '/../'));


// configurations
$config = [

	'db' => [
		'type'     => 'mysql',
		'name'     => 'miniblog',
		'server'   => 'localhost',
		'username' => 'root',
		'password' => 'root',
		'charset'  => 'utf8'
	]

];

//connect db

$db = new PDO("{$config['db']['type']}:host={$config['db']['server']};
	dbname={$config['db']['name']};charset={$config['db']['charset']}",
	$config['db']['username'], $config['db']['password']);

$db ->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);




// global functions
require_once 'functions.php';