<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUpdatingToSearchqueriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('searchqueries', function(Blueprint $table)
		{

			$table->boolean('updating')->after('name')->default(0);

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

			$table->dropColumn('updating');

		});
	}

}
