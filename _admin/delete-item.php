<?php

require '../_inc/config.php';

$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);

		if (!$post_id) {
			flash()->error('You are about to delete what ?');
			redirect('back');
		}


//Delete

$query = $db-> prepare( "
DELETE FROM posts
WHERE id = :post_id

");

$delete = $query -> execute([
	'post_id'=>$post_id
]);


if (! $delete) {
	flash()->warning('Sooory mate');
	redirect('back');
}


$query = $db->prepare( "
DELETE FROM posts_tags
WHERE post_id = :post_id

");

$delete_tags = $query -> execute([
	'post_id' => $post_id
]);

flash()->success('Yo post has been successfuly deleted !!');
redirect('/');


