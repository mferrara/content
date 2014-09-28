<?php

namespace HiveMind;


class ArticleProcessor {

	public static function fire($searchquery)
	{
		\Log::critical('It fired...');
		// Loop through all content for counters
		$all_text = '';
		$content_types = [];
		$subreddits = [];
		$self_posts = 0;
		$total_posts = 0;
		$authors = [];
		foreach($searchquery->articles as $art)
		{
			$all_text .= $art->post_text;

			if(isset($content_types[$art->content_type]))
				$content_types[$art->content_type]++;
			else
				$content_types[$art->content_type] = 1;

			if(isset($subreddits[$art->subreddit_id]))
				$subreddits[$art->subreddit_id]++;
			else
				$subreddits[$art->subreddit_id] = 1;

			if(isset($authors[$art->author_id]))
				$authors[$art->author_id]++;
			else
				$authors[$art->author_id] = 1;

			if($art->is_self == 1)
				$self_posts++;

			$total_posts++;

			if(isset($base_domains[$art->base_domain]))
				$base_domains[$art->base_domain]++;
			else
				$base_domains[$art->base_domain] = 1;
		}
		\Log::critical('Finished Loop...');
		// Convert subreddit_ids into names
		$subs = [];
		foreach($subreddits as $sub_id => $count)
		{
			$subs[\Subreddit::find($sub_id)->name] = $count;
		}

		$auths = [];
		foreach($authors as $author_id => $count)
		{
			$auths[\Author::find($author_id)->name] = $count;
		}

		// Reverse sort arrays
		arsort($content_types);
		arsort($subs);
		arsort($auths);
		\Log::critical('Before phrases...');
		if(\App::environment() == 'production')
			$phrases = extractCommonPhrases($all_text, [2,3], 25);
		else
			$phrases = extractCommonPhrases(substr($all_text,0,1000), [2,3], 25);
		\Log::critical('Finished Phrases...');
		$cache = [
			'content_types' => $content_types,
			'subreddits' 	=> $subs,
			'authors'		=> $auths,
			'self_posts' 	=> $self_posts,
			'total_posts'	=> $total_posts,
			'phrases'		=> $phrases,
			'updated'		=> \Carbon\Carbon::now()->toDateTimeString()
		];

		$key = 'searchquery_'.$searchquery->id.'_processed_data';
		// Remove existing cache entry
		\Cache::forget($key);
		// Create a new one
		\Cache::add($key, $cache, \Config::get('hivemind.cache_reddit_requests'));
		\Log::critical('After caching...');
	}

} 