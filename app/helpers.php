<?php
// Used with usort() to sort multi-dimensional arrays
// Example: usort($news, make_comparer(['score', SORT_DESC]));
// Where $news is the array, 'score' is the field you want to sort by and SORT_DESC or SORT_ASC is the order
function make_comparer() {
	// Normalize criteria up front so that the comparer finds everything tidy
	$criteria = func_get_args();
	foreach ($criteria as $index => $criterion) {
		$criteria[$index] = is_array($criterion)
			? array_pad($criterion, 3, null)
			: array($criterion, SORT_ASC, null);
	}

	return function($first, $second) use (&$criteria) {
		foreach ($criteria as $criterion) {
			// How will we compare this round?
			list($column, $sortOrder, $projection) = $criterion;
			$sortOrder = $sortOrder === SORT_DESC ? -1 : 1;

			// If a projection was defined project the values now
			if ($projection) {
				$lhs = call_user_func($projection, $first[$column]);
				$rhs = call_user_func($projection, $second[$column]);
			}
			else {
				$lhs = $first[$column];
				$rhs = $second[$column];
			}

			// Do the actual comparison; do not return if equal
			if ($lhs < $rhs) {
				return -1 * $sortOrder;
			}
			else if ($lhs > $rhs) {
				return 1 * $sortOrder;
			}
		}

		return 0; // tiebreakers exhausted, so $first == $second
	};
}

function extract_phrases($string, $ignore_words = [], $num_words_in_phrase = 2, $number_of_phrases = 10)
{

	// Get all the words from the string
	//$words = explode(" ", $string);

	$string = strtolower(trim($string,"'"));
	$string = html_entity_decode($string,null, 'UTF-8');
	$string = remove_urls($string);
	$string = remove_long_words($string,30);

	// Remove everything between paranthesis
	$string = preg_replace("/\([^)]+\)/","",$string);
	// Remove everything between brackets
	$string = preg_replace('/\[[^\]]*\]/', '', $string);

	$string = str_replace("*", "", $string);


	//$string = preg_replace("/[^\w\d ]/ui", '', $string);

	// get all words. Assume any 1 or more letter, number or ' in a row is a word
	preg_match_all('~[a-z0-9\'\/.-]+~',$string,$words);
	$words = $words[0];

	// If there's nothing here, return false
	if(count($words) < 1) return false;

	// Cycle through the words removing ugly stuff
	foreach($words as $key => $word)
	{
		if(stristr($word, 'http')
			|| stristr($word, 'www')
			|| stristr($word, '/')
			|| stristr($word, '\\')
		)
			unset($words[$key]);
	}
	$words = array_values($words);

	// Cycle through the string creating the possible phrase combos
	$phrases = [];
	foreach($words as $k => $word)
	{
		if(isset($words[$k+$num_words_in_phrase]))
		{
			$str = '';

			for($i = 1; $i <= $num_words_in_phrase; $i++)
			{
				$str .= $words[$k + $i] . ' ';
			}

			// If there's the same number of words as we're expecting in the phrase
			if(count(explode(" ", trim($str))) == $num_words_in_phrase)
			{
				// and it actually has letters in it...
				if(!ctype_digit(str_replace(" ", "", $word)))
					$phrases[] = trim($str);
			}

		}
	}

	// Check for ignore words
	foreach($phrases as $key => $phrase)
	{
		if(count($ignore_words) != 0)
		{
			foreach($ignore_words as $ignore)
			{
				$phrase = urldecode(trim($phrase));
				// Check with spaces
				if(stristr($phrase, " ".$ignore." "))
					unset($phrases[$key]);

				// Check at beginning phrase
				if(strpos($phrase, $ignore." ") === 0)
					unset($phrases[$key]);

				// Check at end of phrase
				if(strpos($phrase, " ".$ignore) === strlen($phrase)-strlen(" ".$ignore))
					unset($phrases[$key]);
			}
		}
	}


	$phrases = array_count_values($phrases);

	// reverse sort it (preserving keys, since the keys are the phrases
	arsort($phrases);

	// if limit is specified, return only $limit phrases. otherwise, return all of them
	return ($number_of_phrases > 0) ? array_slice($phrases,0,$number_of_phrases) : $phrases;

}

function remove_urls($string)
{
	$string = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $string);
	return $string;
}

function remove_long_words($string,$max_length)
{
	$snippet = explode(" ",$string);
	$array = array();
	foreach($snippet as $key => $string)
	{
		if(strlen($string) <= $max_length)
		{
			$array[] = $string;
		}
	}
	$snippet = implode(" ",$array);
	return $snippet;
}