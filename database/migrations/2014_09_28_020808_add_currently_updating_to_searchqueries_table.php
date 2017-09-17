<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCurrentlyUpdatingToSearchqueriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('searchqueries', function (Blueprint $table) {

            $table->boolean('currently_updating')->after('name')->default(0);
            $table->dropColumn('updating');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('searchqueries', function (Blueprint $table) {

            $table->dropColumn('currently_updating');
            $table->boolean('updating')->after('name')->default(0);
        });
    }
}
