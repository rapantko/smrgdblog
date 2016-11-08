<?php

// show all errors
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


// require stuff
if( !session_id() ) @session_start();
require_once 'vendor/autoload.php';


// constants & settings
define( 'BASE_URL', 'http://localhost:8888/blog' );
define( 'APP_PATH', realpath(__DIR__ . '/../') );


// configurations
$config = [

	'database' => [
		'database_type' => 'mysql',
		'database_name' => 'blog',
		'server'        => 'localhost',
		'username'      => 'root',
		'password'      => 'root',
		'charset'       => 'utf8'
	]

];



// connect to db



// global functions
require_once 'functions.php';