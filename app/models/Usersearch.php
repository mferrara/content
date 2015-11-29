<?php

class Usersearch extends \Eloquent {

	protected $table    = 'usersearches';
	protected $guarded  = ['id', 'created_at'];
	protected $fillable = [];

	public function searchquery()
	{
		return $this->belongsTo(Searchquery::class);
	}

	public function subreddit()
	{
		return $this->belongsTo(Subreddit::class);
	}

    public function webhookurl()
    {
        return $this->belongsTo(Webhookurl::class);
    }

    public function sendWebhook()
    {
        $articles   = $this->searchquery->articles;

        // Unset the large ->data object
        foreach($articles as $key => $article)
        {
            unset($articles[$key]->data);
        }

        // Filter content_type's
        $images     = $articles->filter(function($article)
        {
            if($article->content_type == 'image')
                return true;

            return false;
        });
        $videos     = $articles->filter(function($article)
        {
            if($article->content_type == 'video')
                return true;

            return false;
        });
        $selfshort  = $articles->filter(function($article)
        {
            if($article->content_type == 'self.short')
                return true;

            return false;
        });
        $selfmedium = $articles->filter(function($article)
        {
            if($article->content_type == 'self.medium')
                return true;

            return false;
        });
        $selflong   = $articles->filter(function($article)
        {
            if($article->content_type == 'self.long')
                return true;

            return false;
        });

        $images_count       = $images->count();
        $videos_count       = $videos->count();
        $selfshort_count    = $selfshort->count();
        $selfmedium_count   = $selfmedium->count();
        $selflong_count     = $selflong->count();

        // Build output content
        $max_words      = 600;
        $text_output    = '';
        $images_output  = [];
        $videos_output  = [];

        if(($images_count + $videos_count + $selfshort_count + $selfmedium_count + $selflong_count) > 0)
        {
            // Text content
            $collective_text    = new \Illuminate\Database\Eloquent\Collection();

            if($selfshort_count)
            {
                if($selfshort_count > 10)
                    $selfshort = $selfshort->random(10);
                $collective_text = $collective_text->merge($selfshort);
            }

            if($selfmedium_count)
            {
                if($selfmedium_count > 5)
                    $selfmedium = $selfmedium->random(5);
                $collective_text = $collective_text->merge($selfmedium);
            }

            if($selflong_count)
            {
                if($selflong_count > 5)
                    $selflong = $selflong->random(5);
                $collective_text = $collective_text->merge($selflong);
            }

            while(count(explode(' ', $text_output)) <= $max_words)
            {
                $text = $collective_text->random(1)->post_text;
                $text_output .= $text."<br /><br />";
            }

            if($images_count)
            {
                if($images_count > 5)
                    $images = $images->random(5);

                foreach($images as $image)
                {
                    $images_output[] = $image->url;
                }
            }

            if($videos_count)
            {
                if($videos_count > 5)
                    $videos = $videos->random(5);

                foreach($videos as $video)
                {
                    $videos_output[] = $video->url;
                }
            }
        }

        $resulting_word_count = count(explode(' ', $text_output));

        $output = json_encode([
            'keyword'       => $this->searchquery->name,
            'content'       => $text_output,
            'word_count'    => $resulting_word_count,
            'images'        => $images_output,
            'videos'        => $videos_output
        ]);

        // Send webhook
        $client = new GuzzleHttp\Client(['base_url' => $this->webhookurl->url]);
        $client->post('/', [
            'body'      => $output
        ]);

        $this->webhook_sent = 1;

        return $this->save();
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

	public static function getSearch($keyword, $search_type = 'plain', $sort_by = 'relevance', $subreddits = 'all', $webhook_url = null)
	{
		// Get the search query
		$query = Searchquery::where('name', $keyword)->first();

		// If there isn't one, create it
		if($query == null)
		{
			$query = Searchquery::create(['name' => $keyword]);
		}

        // Create a Usersearch object
        $data = [
            'searchquery_id' => $query->id
        ];

        if($webhook_url !== null)
        {
            $webhook = Webhookurl::where('url', $webhook_url)->first();
            if($webhook == null)
                $webhook = Webhookurl::create(['url' => $webhook_url]);

            $data['webhookurl_id'] = $webhook->id;
        }
        $usersearch = Usersearch::create($data);

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

				// Set a flag that it's currently updating so further requests to this page won't spawn more
				// The job will set the flag back to false when it completes
				$query->currently_updating = 1;
				$query->save();

				// Fire off the scraping job
				Queue::push('\HiveMind\Jobs\ScrapeReddit@search', $data, 'redditscrape');
			}
		}

		return $usersearch;
	}

}