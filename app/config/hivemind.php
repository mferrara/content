<?php

	return [

		'reddit_sleep'	                    => 4,
        '503_sleep'                         => 3,
		'page_depth'	                    => 5,
		'cache_reddit_search_requests'      => 3600 * 4,  // 4 hours for search queries
		'cache_reddit_subreddit_requests'   => 3600 * 12, // 12 hours for subreddits
		'ignore_words'	=> [
			"with",
			"or",
			"and",
			"-",
			"--",
			"---",
			"for",
			"if",
			"you",
			"your",
			"of",
			"a",
			"has",
			"was",
			"in",
			"the",
			"on",
			"to",
			"i",
			"at",
			"any",
			"it",
			"as",
			"me",
			"be",
			"is",
			"this",
			"from",
			"like",
			"an",
			"his",
			"hey"
		],

	];