<?php

namespace HiveMind\Jobs;

use Aws\CloudFront\Exception\Exception;
use GuzzleHttp\Exception\ServerException;
use HiveMind\Reddit;
use HiveMind\ArticleProcessor;
use Config;
use Subreddit;
use Searchquery;

class ScrapeReddit {

	public function subreddit($job, $data)
	{

		$subreddit		= Subreddit::find($data['subreddit_id']);
		$sort 			= $data['sort_type'];
		$time			= $data['time'];
		$error 			= false;
		$page_depth 	= Config::get('hivemind.page_depth');

		try{
			$scraper = new Reddit();

			foreach($time as $time_frame)
			{
				foreach($sort as $sort_method)
				{
					$scraper->Subreddit($subreddit->name, $page_depth, $sort_method, $time_frame);
				}
			}

			$subreddit->scraped = 1;
			$subreddit->save();
		}
		catch(ServerException $e)
		{
			$error = true;
            \Log::error('Yo, something broke.');
            \Log::error($e->getMessage());
			$job->release();
		}

		if($error == false)
		{
			// Queue up the processing of the articles
			// This is after the model is saved because we're triggering the clearing
			// of this cache by updating of the model

			$subreddit->queueArticleProcessing();
		}

		$job->delete();

	}

	public function search($job, $data)
	{
		$query 			= Searchquery::find($data['searchquery_id']);
		$sort_method    = $data['sort_type'];
		$subs 			= $data['subreddits'];
		$search_type 	= $data['search_type'];
		$time			= $data['time'];
		$error 			= false;
		$page_depth 	= Config::get('hivemind.page_depth');

		try{

			$scraper = new Reddit();

			if(is_array($time))
			{
				foreach($time as $time_frame)
				{
					$scraper->Search($query, $page_depth, $search_type, $sort_method, $time_frame, $subs);
				}
			}
			else
			{
				$scraper->Search($query, $page_depth, $search_type, $sort_method, $time, $subs);
			}

			$query->scraped = 1;
			$query->save();

            // Are there any webhooks that need to be sent for this query?
            $usersearches = $query->usersearches()
                                    ->where('webhookurl_id', '>', 0)
                                    ->where('webhook_sent', 0)
                                    ->get();

            if($usersearches->count() > 0)
            {
                foreach($usersearches as $usersearch)
                {
                    $usersearch->sendWebhook();
                }
            }

		}
		catch(ServerException $e)
		{
			$error = true;
            \Log::error('Yo, something broke.');
            \Log::error($e->getMessage());

			$job->release();
		}

		if($error == false)
		{
			// Queue up the processing of the articles
			// This is after the model is saved because we're triggering the clearing
			// of this cache by updating of the model

			$query->queueArticleProcessing();
		}

		$job->delete();
	}
} 