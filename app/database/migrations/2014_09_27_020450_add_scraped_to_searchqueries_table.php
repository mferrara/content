<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddScrapedToSearchqueriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('searchqueries', function(Blueprint $table)
		{

			$table->boolean('scraped')->default(0);

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('searchqueries', function(Blueprint $table)
		{

			$table->dropColumn('scraped');

		});
	}

}
