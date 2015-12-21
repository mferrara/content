<?php

namespace HiveMind;

use \crodas\TextRank\Config;
use \crodas\TextRank\TextRank;
use \crodas\TextRank\Stopword;
use ForceUTF8\Encoding;

class ArticleProcessor {

	public static function fire($model, $no_keywords = false)
	{
		// Loop through all content for counters
		$all_text = '';
		$content_types  = [];
		$subreddits     = [];
		$base_domains   = [];
        $authors        = [];
		$self_posts     = 0;
		$total_posts    = 0;
		foreach($model->articles as $art)
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

			if(isset($base_domains[$art->basedomain_id]))
				$base_domains[$art->basedomain_id]++;
			else
				$base_domains[$art->basedomain_id] = 1;
		}

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

		$doms = [];
		foreach($base_domains as $basedomain_id => $count)
		{
			$doms[\Basedomain::find($basedomain_id)->name] = $count;
		}

		// Reverse sort arrays
		arsort($content_types);
		arsort($subs);
		arsort($auths);
		arsort($doms);

		/*
		if(\App::environment() == 'production')
			$phrases = extractCommonPhrases($all_text, [2,3], 25);
		else
			$phrases = extractCommonPhrases(substr($all_text,0,1000), [2,3], 25);
		*/
        $keywords = [];
        if($no_keywords === false)
        {
            try{
                $config     = new Config;
                $config->addListener(new Stopword);
                $textrank   = new TextRank($config);
                $text       = Encoding::toUTF8($all_text);
                $text       = preg_replace('/[^a-zA-Z0-9 .,\'-]/', '', $text);
                if(mb_strlen($text))
                    $keywords = $textrank->getKeywords($text);

                foreach($keywords as $key => $keyword)
                {
                    $count = mb_substr_count(mb_strtolower($text), mb_strtolower($keyword));
                    if($count < 2)
                        unset($keywords[$key]);
                }
            }
            catch(\Exception $e)
            {
                \Log::error('ArticleProcessor@fire - '.$model->name);
                \Log::error($e->getMessage());
                \Log::error($e->getTraceAsString());

                return false;
            }
        }
        else
        {
            \Log::error('Running article processing with no-keywords flag due to previous error: '.$model->name);
        }

		$cache = [
			'content_types' => $content_types,
			'subreddits' 	=> $subs,
			'authors'		=> $auths,
			'base_domains'	=> $doms,
			'self_posts' 	=> $self_posts,
			'total_posts'	=> $total_posts,
			'keywords'		=> $keywords,
			'updated'		=> \Carbon\Carbon::now()->toDateTimeString()
		];

		$key = strtolower(get_class($model)).'_'.$model->id.'_processed_data';
		// Remove existing cache entry
		\Cache::forget($key);
		// Create a new one

        $configuration_key = 'hivemind.cache_reddit_search_requests';
        if(get_class($model) == 'Searchquery')
            $configuration_key = 'hivemind.cache_reddit_search_requests';
        if(get_class($model) == 'Subreddit')
            $configuration_key = 'hivemind.cache_reddit_subreddit_requests';

		\Cache::add($key, $cache, \Config::get($configuration_key));

        return true;
	}

} 