<?php

namespace App\Console\Commands;

use App\Searchquery;
use App\Subreddit;
use App\Usersearch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class ResetStuckUpdates extends Command
{

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
    public function handle()
    {
        // Get collection of all 'currently_updating' subreddits
        $subreddits = Subreddit::where('currently_updating', 1)->get();
        foreach ($subreddits as $sub) {
            // If they haven't been updated in 25 min but are on the 'currently_updating' status - they're stuck
            if ($sub->updated_at->diffInMinutes(\Carbon\Carbon::now()) > 25) {

                // Build $data array for the job
                $data['subreddit_id']   = $sub->id;

                if (\App::environment() == 'production') {
                    $data['sort_type']      = ['hot', 'new', 'top'];
                    $data['time']           = ['all', 'year', 'month', 'week'];
                } else {
                    $data['sort_type']      = ['hot'];
                    $data['time']           = ['all'];
                }

                // If it's been scraped then we must be stuck on the processing
                if ($sub->scraped == 1) {
                    // Looks like scraping finished, but processing failed, re-queue the processing
                    Queue::push('\HiveMind\Jobs\ProcessArticles@processSubreddit', $data, 'redditprocessing');

                } else {
                    // Hasn't been scraped, let's reset the subreddit's scraped/cached/updating flags and re-queue it
                    $sub->currently_updating    = 0;
                    $sub->scraped               = 0;
                    $sub->cached                = 0;
                    $sub->save();

                    Queue::push('\HiveMind\Jobs\ScrapeReddit@subreddit', $data, 'redditscrape');
                }

                echo 'Added '.$sub->name." back to queue.\r\n";
            }
        }

        // Get collection of 'currently_updating' search queries
        $searchqueries = Searchquery::where('currently_updating', 1)->get();
        foreach ($searchqueries as $query) {
            // If they haven't been udpated in 25 min but are on the 'currently_updating' status - they're stuck
            if ($query->updated_at->diffInMinutes(\Carbon\Carbon::now()) > 25) {

                // If it's been scraped we must have gotten stuck while processing - re-queue that
                if ($query->scraped == 1) {
                    // Build data array for the job
                    $data['searchquery_id'] = $query->id;

                    Queue::push('\HiveMind\Jobs\ProcessArticles@processSearchquery', $data, 'redditprocessing');
                } else {
                    // Scraping failed - reset the flags and re-queue it
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

}
