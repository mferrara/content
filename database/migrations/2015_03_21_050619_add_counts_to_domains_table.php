<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCountsToDomainsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basedomains', function (Blueprint $table) {
            $table->integer('article_count')->after('name')->default(0);
        });

        Basedomain::chunk(500, function ($collection) {
            foreach ($collection as $model) {
                $model->article_count = $model->articles()->count();
                $model->save();
            }
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->integer('article_count')->after('name')->default(0);
        });

        Author::chunk(500, function ($collection) {
            foreach ($collection as $model) {
                $model->article_count = $model->articles()->count();
                $model->save();
            }
        });

        Schema::table('subreddits', function (Blueprint $table) {
            $table->integer('article_count')->after('name')->default(0);
        });

        Subreddit::chunk(500, function ($collection) {
            foreach ($collection as $model) {
                $model->article_count = $model->articles()->count();
                $model->save();
            }
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('basedomains', function (Blueprint $table) {
            $table->dropColumn('article_count');
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn('article_count');
        });

        Schema::table('subreddits', function (Blueprint $table) {
            $table->dropColumn('article_count');
        });
    }
}
