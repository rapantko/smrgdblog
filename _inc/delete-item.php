<?php

	// include
	require 'config.php';

	// delete item
	$affected = $database->delete('items',
		[ 'id' => $_POST['id'] ]
	);

	// success
	if ( $affected ) {
		flash()->success('Goodbye, todo item!');
		redirect('/');
	}

	// something went wrong
	flash()->error('Something went wrong:(');
	redirect('back');