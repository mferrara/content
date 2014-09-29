<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSubredditIdToUsersearchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('usersearches', function(Blueprint $table)
		{

			$table->integer('subreddit_id')->nullable()->index()->after('searchquery_id');

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('usersearches', function(Blueprint $table)
		{

			$table->dropColumn('subreddit_id');

		});
	}

}
