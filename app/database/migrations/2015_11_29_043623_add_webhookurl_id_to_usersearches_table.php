<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddWebhookurlIdToUsersearchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('usersearches', function(Blueprint $table)
		{
			$table->integer('webhookurl_id')->unsigned()->nullable()->index()->after('subreddit_id');
            $table->boolean('webhook_sent')->default(0)->index()->after('webhookurl_id');

            // Create index for searches of the nature of...
            // where searchquery = foo and where webhookurl_id not null and webhook_sent != 1
            $table->index(['searchquery_id', 'webhookurl_id', 'webhook_sent'], 'is_webhook_sent_search');
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
			$table->dropColumn('webhookurl_id');
            $table->dropColumn('webhook_sent');

            $table->dropIndex('is_webhook_sent_search');
		});
	}

}
