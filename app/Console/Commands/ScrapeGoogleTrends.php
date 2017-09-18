<?php

namespace App\Console\Commands;

use App\Searchquery;
use App\Usersearch;
use Illuminate\Console\Command;

class ScrapeGoogleTrends extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'hivemind:scrapegoogletrends';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grab Google Trends and queue searches.';

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
        $trends = \HiveMind\GoogleTrends::getTrends();

        foreach ($trends as $keyword) {
            $check = Searchquery::where('name', $keyword)->first();

            if ($check == null) {
                Usersearch::getSearch($keyword);
            }
        }
    }

}
