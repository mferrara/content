<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddScrapedToSubredditsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('subreddits', function(Blueprint $table)
		{

			$table->boolean('currently_updating')->after('name');
			$table->boolean('scraped')->after('currently_updating');
			$table->boolean('cached')->after('scraped');

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('subreddits', function(Blueprint $table)
		{

			$table->dropColumn('currently_updating');
			$table->dropColumn('scraped');
			$table->dropColumn('cached');

		});
	}

}
