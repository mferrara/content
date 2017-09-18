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
        $subreddits = Subreddit::where('currently_updating', 1)->get();
        foreach ($subreddits as $sub) {
            if ($sub->updated_at->diffInMinutes(\Carbon\Carbon::now()) > 25) {
                $data['subreddit_id']   = $sub->id;

                if ($sub->scraped == 1) {
                    // Looks like scraping finished, but processing failed, re-queue the processing
                    Queue::push('\HiveMind\Jobs\ProcessArticles@processSubreddit', $data, 'redditprocessing');
                } else {
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
        foreach ($searchqueries as $query) {
            if ($query->updated_at->diffInMinutes(\Carbon\Carbon::now()) > 25) {
                if ($query->scraped == 1) {
                    $data['searchquery_id'] = $query->id;
                    // Looks like scraping finished, but processing failed, re-queue the processing
                    Queue::push('\HiveMind\Jobs\ProcessArticles@processSearchquery', $data, 'redditprocessing');
                } else {
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
