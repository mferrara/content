<?php

class Usersearch extends \Eloquent {

	protected $table = 'usersearches';
	protected $guarded = ['id'];
	protected $fillable = [];

	public function searchquery()
	{
		return $this->belongsTo('Searchquery');
	}

	public static function search($keyword)
	{
		// Get the search query
		$query = Searchquery::where('name', $keyword)->first();

		// If there isn't one, create it
		if($query == null)
		{
			$query = Searchquery::create(['name' => $keyword]);
		}

		// If the query hasn't been scraped, queue it
		if($query->scraped == 0)
		{
			$data['searchquery_id'] = $query->id;

			if(App::environment() == 'production')
				$data['page_depth'] 	= 5;
			else
				$data['page_depth'] 	= 1;

			$data['sort_type'] 		= 'relevance';
			$data['subreddits'] 	= 'all';

			Queue::push('\HiveMind\Jobs\ScrapeReddit@fullScrape', $data, 'redditscrape');
		}

		return Usersearch::create(['searchquery_id' => $query->id]);
	}

}