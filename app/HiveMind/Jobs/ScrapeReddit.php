<?php

namespace HiveMind\Jobs;

use Aws\CloudFront\Exception\Exception;
use GuzzleHttp\Exception\ServerException;
use HiveMind\Reddit;
use HiveMind\ArticleProcessor;

class ScrapeReddit {

	public function subreddit($job, $data)
	{

		$subreddit		= \Subreddit::find($data['subreddit_id']);
		\Log::error('Job start - r/'.$subreddit->name);
		$sort 			= $data['sort_type'];
		$time			= $data['time'];
		$error 			= false;

		$page_depth 	= \Config::get('hivemind.page_depth');

		try{
			\Log::error('Before Scraping...');

			$scraper = new Reddit();

			foreach($time as $t)
			{
				foreach($sort as $s)
				{
					$scraper->Subreddit($subreddit->name, $page_depth, $s, $t);
				}
			}

			\Log::error('After Scraping...');

			$subreddit->scraped = 1;
			$subreddit->save();
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

				ArticleProcessor::fire($subreddit);

				\Log::error('After Processing');

				$subreddit->cached 				= 1;
				$subreddit->currently_updating 	= 0;
				$subreddit->save();

				\Log::error('Query Saved - '.$subreddit->name);

			}
			catch(Exception $e)
			{
				\Log::error('Processing Failed - Adding '.$subreddit->name.' to queue');
				\Log::error($e->getMessage());

				// queue it back up
				$subreddit_id = $subreddit->id;
				\Queue::push(function($job) use($subreddit_id)
				{
					ArticleProcessor::fire(\Subreddit::find($subreddit_id));
					$job->delete();
				}, 'redditprocessing');

				$subreddit->cached = 0;
				$subreddit->currently_updating = 0;
				$subreddit->save();

				\Log::error('Model saved, I\'m out.');

				$job->delete();
			}
		}

		$job->delete();

	}

	public function search($job, $data)
	{
		$query 			= \Searchquery::find($data['searchquery_id']);
		\Log::error('Job start - '.$query->name);
		$sort 			= $data['sort_type'];
		$subs 			= $data['subreddits'];
		$search_type 	= $data['search_type'];
		$time			= $data['time'];
		$error 			= false;

		$page_depth 	= \Config::get('hivemind.page_depth');

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