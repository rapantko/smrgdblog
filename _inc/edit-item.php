<?php

	// include
	require 'config.php';

	// update item
	$affected = $database->update('items',
		[ 'text' => $_POST['message'] ],
		[ 'id' => $_POST['id'] ]
	);

	// success
	if ( $affected )
	{
		flash()->success('Yay, changed it!');
		redirect('/');
	}
	elseif ( $affected === 0 )
	{
		flash()->warning('You forgot to change it:(');
		redirect('back');
	}

	// something went wrong
	flash()->error('Something went wrong:(');
	redirect('back');