<?php


/**
 * Plain
 *
 * Escape (though not from New York)
 *
 * @param  string $str
 * @return string
 */
function plain( $str )
{
	return htmlspecialchars( $str, ENT_QUOTES );
}


/**
 * Word Limiter
 *
 * Limits a string to X number of words.
 *
 * @param	string
 * @param	int
 * @param	string	the end character. Usually an ellipsis
 * @return	string
 */
function word_limiter($str, $limit = 100, $end_char = '&#8230;')
{
	if (trim($str) === '') {
		return $str;
	}

	preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

	if (strlen($str) === strlen($matches[0])) {
		$end_char = '';
	}

	return rtrim($matches[0]).$end_char;
}

