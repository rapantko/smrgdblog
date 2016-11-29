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

	if (!filter_var($id, FILTER_VALIDATE_INT)) {
		return false;
	}

	global $db;

	$query = $db->prepare("
	
	SELECT p.* , GROUP_CONCAT(t.tag SEPARATOR '||') AS tags
	FROM posts p 
	LEFT JOIN posts_tags pt on (p.id = pt.post_id)
	LEFT JOIN tags t ON (t.id = pt.tag_id)
	WHERE p.id = :id 
	GROUP BY p.id
	
	");

	$query->execute(['id' => $id]);

	if ($query->rowCount() === 1) {
		$result = $query->fetch(PDO::FETCH_ASSOC);


		if ($auto_format) {
			$result = format_post($result);
		} else {
			$result = (object)$result;
		}
	} else {
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
function get_posts_by_tag($tag = '', $auto_format = true)
{
	// we have no id
	if (!$tag && !$tag = segment(2)) {
		return false;
	}

	//$tag = urldecode($tag);
	//$tag = filter_var ($tag , FILTER_SANITIZE_STRING);

	global $db;

	$query = $db->prepare("
	
	SELECT p.* , GROUP_CONCAT(t.tag SEPARATOR '||') AS tags
	FROM posts p 
	LEFT JOIN posts_tags pt on (p.id = pt.post_id)
	LEFT JOIN tags t ON (t.id = pt.tag_id)
	WHERE t.tag = :tag 
	GROUP BY p.id
	
	");

	$query->execute(['tag' => $tag]);

	if ($query->rowCount()) {
		$results = $query->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$results = [];
	}

	if ($auto_format) {
		$results = array_map('format_post', $results);
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


	if ($query->rowCount()) {
		$results = $query->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$results = [];
	}

	if ($auto_format) {
		$results = array_map('format_post', $results);
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

function format_post($post)
{
	//CLEANING OUT THE SHIT OUT OF A TEXT
	$post['title'] = plain($post['title']);
	$post['text']  = plain($post['text']);
	$post['tags']  = $post['tags'] ? explode('||', $post['tags']) : [];
	$post['tags']  = array_map('plain', $post['tags']);

	// Create tag links

	if ($post['tags']) {
		foreach ($post['tags'] as $tag) {
			$post['links'][$tag] = BASE_URL . '/tag/' . urlencode($tag);
			$post['links'][$tag] = filter_var($post['links'][$tag], FILTER_SANITIZE_URL);
		}
	}

	//CREATE LINK
	$post['link'] = get_post_link($post);
	//DOIN TIMES
	$post['timestamp'] = strtotime($post['created_at']);
	$post['time']      = str_replace(' ', '&nbsp', date('j M Y, G:i', $post['timestamp']));
	$post['date']      = date('Y-m-d', $post['timestamp']);

	//LIMIT THAT SHIT
	$post['teaser'] = word_limiter($post['text'], 40);

	$post['text'] = filter_url(add_paragraphs($post['text']));


	return (object)$post;

}

/**
 * Get post link
 *
 * creates url link of the post
 *
 * @param $post
 * @param string $type
 * @return mixed|string
 */
function get_post_link($post, $type = 'post')
{
	if (is_object($post)) {
		$id   = $post->id;
		$slug = $post->slug;
	} else {
		$id   = $post['id'];
		$slug = $post['slug'];
	}

	$link = BASE_URL . "/$type/$id";

	if ($type === 'post') {
		$link .= "/$slug";
	}
	$link = filter_var($link, FILTER_SANITIZE_URL);

	return $link;
}


/**
 * Get all tags
 *
 * Returns all tags or tags for single post based on $post_id parameter
 *
 * @param int $post_id
 * @return array
 */
function get_all_tags($post_id = 0)
{
	global $db;

	$query = $db->query("SELECT * FROM tags");


	$results = $query->rowCount() ? $query->fetchAll(PDO::FETCH_OBJ) : [];

	if ($post_id) {
		$query = $db->prepare(" SELECT * FROM tags t JOIN
 		posts_tags pt ON t.id = pt.tag_id
 		 WHERE pt.post_id = :id ");

		$query->execute(['id' => $post_id]);

		if ($query->rowCount()) {

			$tags_for_post = $query->fetchAll(PDO::FETCH_COLUMN);

			foreach ($results as $key => $tag) {

				if (in_array($tag->id, $tags_for_post)) {
					$results[$key]->checked = true;
				}

			}
		}

	}


	return $results;
}


/**
 * Validate post
 *
 * sanitizes and validates our post
 *
 * @return array|bool
 */
function validate_post() {

	$title   = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
	$text    = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
	$tags    = filter_input(INPUT_POST, 'tags', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);

//title , id and text are required

	if (isset($_POST['post-id'])) {
		$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);

		if (!$post_id) {
			flash()->error('You are about to edit what ?');
		}
	}
	else {
		$post_id = false;
	}




	if (!$title = trim($title)) {
		flash()->error('You forgot your title , maaaan !!');
	}

	if (!$text = trim($text)) {
		flash()->error('Where is the text, huh ??');
	}

//Error messages have been found, so you will be redirected back home

	if (flash()->hasMessages()) {
		return false;
	}

	return compact(
	'post_id','title','text','tags',$post_id,$title,$text,$tags
	);
}