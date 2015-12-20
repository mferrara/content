<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ResetStuckUpdates extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hivemind:resetstuckupdates';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Reset stuck keywords/subreddits.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$subreddits = Subreddit::where('currently_updating', 1)->get();
        foreach($subreddits as $sub)
        {
            if($sub->updated_at->diffInMinutes(\Carbon\Carbon::now()) > 25)
            {
                $data['subreddit_id']   = $sub->id;

                if($sub->scraped == 1)
                {
                    // Looks like scraping finished, but processing failed, re-queue the processing
                    Queue::push('\HiveMind\Jobs\ProcessArticles@processSubreddit', $data, 'redditprocess');
                }
                else
                {
                    $sub->currently_updating    = 0;
                    $sub->scraped               = 0;
                    $sub->cached                = 0;
                    $sub->save();

                    Queue::push('\HiveMind\Jobs\ScrapeReddit@subreddit', $data, 'redditscrape');
                }

                echo 'Added '.$sub->name." back to queue.\r\n";
            }
        }

        $searchqueries = Searchquery::where('currently_updating', 1)->get();
        foreach($searchqueries as $query)
        {
            if($query->updated_at->diffInMinutes(\Carbon\Carbon::now()) > 25)
            {
                if($query->scraped == 1)
                {
                    $data['searchquery_id'] = $query->id;
                    // Looks like scraping finished, but processing failed, re-queue the processing
                    Queue::push('\HiveMind\Jobs\ProcessArticles@processSearchquery', $data, 'redditprocessing');
                }
                else
                {
                    $query->currently_updating  = 0;
                    $query->scraped             = 0;
                    $query->cached              = 0;
                    $query->save();

                    // Re-queue the search
                    Usersearch::getSearch($query->name);
                }

                echo 'Added '.$query->name." back to queue.\r\n";
            }
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(

		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(

		);
	}

}
