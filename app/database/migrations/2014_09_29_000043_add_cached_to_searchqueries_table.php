<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCachedToSearchqueriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('searchqueries', function(Blueprint $table)
		{

			$table->boolean('cached')->after('scraped')->index();

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

			$table->dropColumn('cached');

		});
	}

}
