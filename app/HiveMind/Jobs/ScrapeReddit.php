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
		ini_set('MAX_EXECUTION_TIME', 300);

		$query = \Searchquery::find($data['searchquery_id']);
		$sort = $data['sort_type'];
		$subs = $data['subreddits'];

		if(\App::environment() == 'production')
			$page_depth = 5;
		else
			$page_depth = 1;

		$scraper = new Reddit();
		$scraper->Search($query, $page_depth, 'plain', $sort, 'all', $subs);
		$scraper->Search($query, $page_depth, 'plain', $sort, 'year', $subs);
		$scraper->Search($query, $page_depth, 'plain', $sort, 'month', $subs);
		$scraper->Search($query, $page_depth, 'plain', $sort, 'week', $subs);

		$query->scraped = 1;
		$query->save();

		$job->delete();
	}
} 