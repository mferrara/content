<?php

namespace HiveMind\Jobs;

use Aws\CloudFront\Exception\Exception;
use GuzzleHttp\Exception\ServerException;
use HiveMind\Reddit;
use HiveMind\ArticleProcessor;

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

			$query->scraped = 1;
			$query->save();
		}
		catch(ServerException $e)
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

			try{

				ArticleProcessor::fire($query);

				\Log::error('After Processing');

				$query->cached 				= 1;
				$query->currently_updating 	= 0;
				$query->save();

				\Log::error('Query Saved - '.$query->name);

			}
			catch(Exception $e)
			{
				\Log::error('Processing Failed - Adding '.$query->name.' to queue');
				\Log::error($e->getMessage());
				
				// queue it back up
				$searchquery_id = $query->id;
				\Queue::push(function($job) use($searchquery_id)
				{
					ArticleProcessor::fire(\Searchquery::find($searchquery_id));
					$job->delete();
				}, 'redditprocessing');

				$query->cached = 0;
				$query->currently_updating = 0;
				$query->save();

				\Log::error('Model saved, I\'m out.');

				$job->delete();
			}
		}

		$job->delete();
	}
} 