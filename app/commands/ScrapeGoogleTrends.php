<?php

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;

class ScrapeGoogleTrends extends ScheduledCommand {

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
	public function fire()
	{
		$trends = \HiveMind\GoogleTrends::getTrends();

		foreach($trends as $keyword)
		{
			$check = Searchquery::where('name', $keyword)->first();

			if($check !== null)
				Usersearch::search($keyword);
		}

	}

	public function schedule(Schedulable $scheduler)
	{
		return $scheduler->everyHours(12);
	}

}
