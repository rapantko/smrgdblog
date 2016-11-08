<?php

	require_once '_inc/config.php';

	$routes = [

		// HOMEPAGE
		'/' => [
			'GET'  => 'home.php'
		],

		// POST
		'/post' => [
			'GET'  => 'post.php',            // show post
			'POST' => '_inc/post-add.php',   // add new post
		],

		// EDIT
		'/edit' => [
			'GET'  => 'edit.php',            // edit form
			'POST' => '_inc/post-edit.php',  // store new values
		],

		// DELETE
		'/delete' => [
			'GET'  => 'delete.php',           // delete form
			'POST' => '_inc/post-delete.php', // make the delete
		],
	];

	$page   = segment(1);
	$method = $_SERVER['REQUEST_METHOD'];

	if ( ! isset( $routes["/$page"][$method] ) ) {
		show_404();
	}

	require $routes["/$page"][$method];