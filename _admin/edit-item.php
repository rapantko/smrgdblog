<?php

require '../_inc/config.php';

if (! $data = validate_post()) {
	redirect('back');
}

extract($data);

//update post

$update_post = $db->prepare("
UPDATE posts SET
title = :title,
text = :text 
WHERE id = :post_id

");

$update_post->execute([
	'title'   => $title,
	'text'    => $text,
	'post_id' => $post_id
]);

//delete all tags

$delete_tags = $db->prepare("
DELETE FROM posts_tags
WHERE post_id = :post_id

");

$delete_tags->execute([
	'post_id' => $post_id
]);

//insert new tags

$post = get_post($post_id, false);

if (isset($tags) && $tags = array_filter($tags)) {

	foreach ($tags as $tag_id) {
		$insert_tags = $db->prepare("
	INSERT INTO posts_tags
	VALUES (:post_id, :tag_id)
	");

		$insert_tags->execute([
			'post_id' => $post_id,
			'tag_id'  => $tag_id
		]);
	}

}

//redirect back

redirect(get_post_link($post));