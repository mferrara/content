<?php

class Usersearch extends \Eloquent {

	protected $table = 'usersearches';
	protected $guarded = ['id'];
	protected $fillable = [];

	public function searchquery()
	{
		return $this->belongsTo('Searchquery');
	}

	public static function search($keyword, $page_depth = 1, $search_type = 'plain', $sort_by = 'relevance', $time = 'all', $subreddits = 'all')
	{
		// Get the search query
		$query = Searchquery::where('name', $keyword)->first();

		// If there isn't one, create it
		if($query == null)
		{
			$query = Searchquery::create(['name' => $keyword]);
		}

		// If the query hasn't been scraped, scrape it it
		// Or it's stale
		$seconds_since_last_update = strtotime(\Carbon\Carbon::now())- strtotime($query->updated_at);

		// If it's not currently updating right now...(let's not spawn updates in the queue everytime a user refreshes the page)
		if($query->currently_updating == 0)
		{
			// If this query has either never been scraped or it's been too long since the last time
			if($query->scraped == 0 || $seconds_since_last_update > Config::get('hivemind.cache_reddit_requests'))
			{
				$data['searchquery_id'] = $query->id;
				$data['page_depth'] 	= $page_depth;
				$data['search_type']	= $search_type;
				$data['sort_type'] 		= $sort_by;
				$data['subreddits'] 	= $subreddits;
				$data['time']			= ['all', 'year', 'month', 'week'];

				// Set a flag that it's currently updating to further requests to this page won't spawn more
				// The job will set the flag back to false when it completes
				$query->currently_updating = 1;
				$query->save();

				// Fire off the scraping job
				Queue::push('\HiveMind\Jobs\ScrapeReddit@fullScrape', $data, 'redditscrape');
			}
		}

		// Return a Usersearch object
		return Usersearch::create(['searchquery_id' => $query->id]);
	}

}