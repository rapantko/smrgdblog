<?php

require '../_inc/config.php';

if (! $data = validate_post()) {
	redirect('back');
}

extract($data);

$slug = slugify($text);

//Insert new post

$query = $db -> prepare(
	'INSERT INTO posts (title, text,slug)
VALUES (:title,:text, :slug)'
);

$insert = $query -> execute([
	'title'=>$title,
	'text'=>$text,
	'slug'=>$slug
]);

if (! $insert) {
	flash()->warning('Sorry , babe');
	redirect('back');
	
}

//Success

$post_id = $db->lastInsertId();
if (isset($tags) && $tags = array_filter($tags)) {

	foreach ( $tags as $tag_id) {
		$insert_tags = $db -> prepare('
		INSERT INTO posts_tags VALUES (:post_id , :tag_id)
		');

		$insert_tags -> execute([
			'post_id' => $post_id,
			'tag_id' => $tag_id
		]);
	}
}

redirect(get_post_link([
	'id' => $post_id,
	'slug' => $slug
]));