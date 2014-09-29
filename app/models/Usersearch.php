<?php

class Usersearch extends \Eloquent {

	protected $table = 'usersearches';
	protected $guarded = ['id'];
	protected $fillable = [];

	public function searchquery()
	{
		return $this->belongsTo('Searchquery');
	}

	public function subreddit()
	{
		return $this->belongsTo('Subreddit');
	}

	public static function getSubreddit($subreddit)
	{
		// Get the subreddit
		$sub = Subreddit::where('name', $subreddit)->first();

		if($sub == null)
		{
			$sub = Subreddit::create(['name' => $subreddit]);
		}

		$subreddit = $sub;

		if($subreddit->currently_updating == 0)
		{
			if($subreddit->scraped == 0 || $subreddit->isStale())
			{
				if(App::environment() == 'production')
				{
					$data['sort_type'] 		= ['hot', 'new', 'top'];
					$data['time']			= ['all', 'year', 'month', 'week'];
				}
				else
				{
					$data['sort_type'] 		= ['hot'];
					$data['time']			= ['all'];
				}
				$data['subreddit_id']	= $subreddit->id;

				$subreddit->currently_updating = 1;
				$subreddit->save();

				// Fire off the scraping job
				Queue::push('\HiveMind\Jobs\ScrapeReddit@subreddit', $data, 'redditscrape');
			}
		}

		// Return a Usersearch object
		return Usersearch::create(['subreddit_id' => $subreddit->id]);
	}

	public static function getSearch($keyword, $search_type = 'plain', $sort_by = 'relevance', $subreddits = 'all')
	{
		// Get the search query
		$query = Searchquery::where('name', $keyword)->first();

		// If there isn't one, create it
		if($query == null)
		{
			$query = Searchquery::create(['name' => $keyword]);
		}

		// If it's not currently updating right now...(let's not spawn updates in the queue everytime a user refreshes the page)
		if($query->currently_updating == 0)
		{
			// If this query has either never been scraped or it's been too long since the last time
			if($query->scraped == 0 || $query->isStale())
			{
				$data['searchquery_id'] = $query->id;
				$data['search_type']	= $search_type;
				$data['sort_type'] 		= $sort_by;
				$data['subreddits'] 	= $subreddits;

				if(App::environment() == 'production')
					$data['time']			= ['all', 'year', 'month', 'week'];
				else
					$data['time']			= ['all'];

				// Set a flag that it's currently updating to further requests to this page won't spawn more
				// The job will set the flag back to false when it completes
				$query->currently_updating = 1;
				$query->save();

				// Fire off the scraping job
				Queue::push('\HiveMind\Jobs\ScrapeReddit@search', $data, 'redditscrape');
			}
		}

		// Return a Usersearch object
		return Usersearch::create(['searchquery_id' => $query->id]);
	}

}