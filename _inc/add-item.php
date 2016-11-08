<?php

	// include
	require 'config.php';

	// add new stuff
	$id = $database->insert('items', [
		'text' => $_POST['message']
	]);

	// ajax
	if ( is_ajax() )
	{
		header('Content-Type: application/json');

		if ( ! $id )
		{
			$message = json_encode([
				'status' => 'error',
				'message' => 'something went wrong:('
			]);
		}
		else
		{
			$message = json_encode([
				'status' => 'success',
				'id' => $id
			]);
		}

		die( $message );
	}

	// non ajax
	if ( ! $id ) {
		flash()->error('Something went wrong:(');
	} else {
		flash()->success('Yay, hello new item!');
	}

	redirect('/');