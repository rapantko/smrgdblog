<?php


/**
 * Get Post
 *
 * Tries to fetch a DB post based on URI segment 
 * Returns false if unable
 *
 * @param  integer  id of the post to get
 * @return DB post  or false
 */

function get_post($id = 0)
{
	// we have no id
	if (!$id && !$id = segment(2)) {
		return false;
	}

	global $database;

	$item = $database->get("items", "text", [
		"id" => $_GET['id']
	]);

	if (!$item) {
		return false;
	}

	return $item;
}

/*
Get posts
*/


function get_posts()
{
	global $db;

	$query = $db->query("
	
	SELECT p.* , GROUP_CONCAT(t.tag SEPARATOR '||') AS tags
	FROM posts p 
	LEFT JOIN posts_tags pt on (p.id = pt.post_id)
	LEFT JOIN tags t ON (t.id = pt.tag_id)
	GROUP BY p.id
	
	");


	if ($query ->rowCount()) {
		$results = $query->fetchAll (PDO::FETCH_ASSOC);
	}
	else {
		$results = [];
	}

	return $results;
}

function format_post($post) {
	//CLEANING OUT THE SHIT OUT OF A TEXT
	$post['title']= plain($post['title']);
	$post['text'] = plain($post['text']);
	$post['tags'] = $post['tags'] ? explode('||', $post['tags']) : [];
	$post['tags'] = array_map('plain' ,$post['tags']);

	//CREATE LINK
	$post['link'] = BASE_URL . "/post/{$post['id']}/{$post['slug']}";
	$post['link'] = filter_var($post['link'], FILTER_SANITIZE_URL);

	//DOIN TIMES
	$post['timestamp'] = strtotime($post['created_at']);
	$post['time'] = str_replace(' ' , '&nbsp',date('j M Y, G:i', $post['timestamp'] ));
	$post['date'] = date('Y-m-d',$post['timestamp']);

	//LIMIT THAT SHIT

	$post['teaser'] = word_limiter($post['text'], 40);


	
	return (object)$post;


}
