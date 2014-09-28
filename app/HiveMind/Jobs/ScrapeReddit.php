<?php

namespace HiveMind\Jobs;

use GuzzleHttp\Exception\ServerException;
use HiveMind\Reddit;

class ScrapeReddit {

	public function fire($job, $data)
	{

		$query = \Searchquery::find($data['searchquery_id']);
		$sort = $data['sort_type'];
		$time = $data['time'];
		$subs = $data['subreddits'];
		$depth = $data['page_depth'];

		$scraper = new Reddit();
		$scraper->Search($query, $depth, 'plain', $sort, $time, $subs);
		$job->delete();

	}

	public function fullScrape($job, $data)
	{
		$query 			= \Searchquery::find($data['searchquery_id']);
		\Log::error('Job start - '.$query->name);
		$sort 			= $data['sort_type'];
		$subs 			= $data['subreddits'];
		$search_type 	= $data['search_type'];
		$time			= $data['time'];
		$error 			= false;

		if(\App::environment() == 'production')
			$page_depth = 5;
		else
			$page_depth = 1;

		try{
			\Log::error('Before Scraping...');

			$scraper = new Reddit();

			if(is_array($time))
			{
				foreach($time as $t)
				{
					$scraper->Search($query, $page_depth, $search_type, $sort, $t, $subs);
				}
			}
			else
			{
				$scraper->Search($query, $page_depth, $search_type, $sort, $time, $subs);
			}
			\Log::error('After Scraping...');
		}
		catch(\GuzzleHttp\Exception\ServerException $e)
		{
			$error = true;
			$job->release();
		}

		\Log::error('Before Processing');

		if($error == false)
		{
			// Queue up the processing of the articles
			// This is after the model is saved because we're triggering the clearing
			// of this cache by updating of the model
			\HiveMind\ArticleProcessor::fire($query);

			\Log::error('After Processing');

			$query->scraped = 1;
			$query->currently_updating = 0;
			$query->save();

			\Log::error('Query Saved - '.$query->name);

			$job->delete();
		}
	}
} 