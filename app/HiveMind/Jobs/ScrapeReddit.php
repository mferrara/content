<?php

namespace HiveMind\Jobs;

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
		$sort 			= $data['sort_type'];
		$subs 			= $data['subreddits'];
		$search_type 	= $data['search_type'];
		$time			= $data['time'];

		if(\App::environment() == 'production')
			$page_depth = 5;
		else
			$page_depth = 1;

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

		$query->scraped = 1;
		$query->updating = 0;
		$query->save();

		// Queue up the processing of the articles
		// This is after the model is saved because we're triggering the clearing
		// of this cache by updating of the model
		\HiveMind\ArticleProcessor::fire($query);

		$job->delete();
	}
} 