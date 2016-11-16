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

function get_post($id = 0, $auto_format = true)
{
	// we have no id
	if (!$id && !$id = segment(2)) {
		return false;
	}

	// id must be integer

	if ( !filter_var($id , FILTER_VALIDATE_INT)) {
		return false;
	}

	global $db ;

	$query = $db->prepare("
	
	SELECT p.* , GROUP_CONCAT(t.tag SEPARATOR '||') AS tags
	FROM posts p 
	LEFT JOIN posts_tags pt on (p.id = pt.post_id)
	LEFT JOIN tags t ON (t.id = pt.tag_id)
	WHERE p.id = :id 
	GROUP BY p.id
	
	");

	$query -> execute(['id' => $id]);

	if ($query ->rowCount()=== 1) {
		$result = $query->fetch(PDO::FETCH_ASSOC);
	}


	if ($auto_format) {
		$result = format_post($result);
	}

	else {
		$result = [];
	}
	return $result;

}


/**
 * Get Posts by tag
 *
 * return all posts that have $tag includeds
 *
 *
 * @param string $tag
 * @param bool $auto_format
 * @return array|bool
 */
function get_posts_by_tag($tag ='', $auto_format = true)
{
	// we have no id
	if (!$tag && !$tag = segment(2)) {
		return false;
	}

	//$tag = urldecode($tag);
	//$tag = filter_var ($tag , FILTER_SANITIZE_STRING);

	global $db ;

	$query = $db->prepare("
	
	SELECT p.* , GROUP_CONCAT(t.tag SEPARATOR '||') AS tags
	FROM posts p 
	LEFT JOIN posts_tags pt on (p.id = pt.post_id)
	LEFT JOIN tags t ON (t.id = pt.tag_id)
	WHERE t.tag = :tag 
	GROUP BY p.id
	
	");

	$query -> execute(['tag' => $tag]);

	if ($query ->rowCount()) {
		$results = $query->fetchAll (PDO::FETCH_ASSOC);
	}
	else {
		$results = [];
	}

	if ($auto_format) {
		$results = array_map('format_post' , $results);
	}
	return $results;

}



/**
 * Get Posts
 *
 * Fetch posts from our DB
 *
 * @return array
 */
function get_posts($auto_format = true)
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

	 if ($auto_format) {
		 $results = array_map('format_post' , $results);
	 }
	return $results;
}







/**
 * Format post
 *
 * Configures proper format to our blog posts
 *
 * @param $post
 * @return object
 */

function format_post($post) {
	//CLEANING OUT THE SHIT OUT OF A TEXT
	$post['title']= plain($post['title']);
	$post['text'] = plain($post['text']);
	$post['tags'] = $post['tags'] ? explode('||', $post['tags']) : [];
	$post['tags'] = array_map('plain' ,$post['tags']);

	// Create tag links

	if ( $post['tags'])  foreach ($post['tags'] as $tag ) {
		$post['links'][$tag] = BASE_URL.'/tag/'. urlencode($tag) ;
		$post['links'][$tag] = filter_var($post['links'][$tag], FILTER_SANITIZE_URL);
	}

	//CREATE LINK
	$post['link'] = BASE_URL . "/post/{$post['id']}/{$post['slug']}";
	$post['link'] = filter_var($post['link'], FILTER_SANITIZE_URL);

	//DOIN TIMES
	$post['timestamp'] = strtotime($post['created_at']);
	$post['time'] = str_replace(' ' , '&nbsp',date('j M Y, G:i', $post['timestamp'] ));
	$post['date'] = date('Y-m-d',$post['timestamp']);

	//LIMIT THAT SHIT
	$post['teaser'] = word_limiter($post['text'], 40);

	$post['text'] = filter_url(add_paragraphs($post['text']));


	
	return (object)$post;

}

