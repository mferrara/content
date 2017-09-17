<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticlesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id')->unsigned();

            $table->string('reddit_id');
            $table->string('fullname')->unique();
            $table->string('type');
            $table->string('content_type');
            $table->integer('subreddit_id')->unsigned()->index();
            $table->integer('character_count')->nullable();
            $table->integer('word_count')->nullable();
            $table->integer('paragraph_count')->nullable();
            $table->binary('post_text')->nullable();
            $table->binary('post_text_html')->nullable();
            $table->integer('author_id')->unsigned()->index();
            $table->integer('score');
            $table->integer('ups');
            $table->integer('downs');
            $table->boolean('nsfw');
            $table->string('permalink', 2083);
            $table->string('url', 2083);
            $table->string('base_domain')->nullable();
            $table->bigInteger('created');
            $table->boolean('is_self');
            $table->string('title', 2083);
            $table->string('slug', 2083);
            $table->integer('num_comments');
            $table->boolean('comments_scraped')->default(false);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->index('base_domain');
            $table->index('content_type');
            $table->index('is_self');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('articles');
    }
}
