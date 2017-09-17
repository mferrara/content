<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('subreddit_id')->unsigned()->index();
            $table->integer('author_id')->unsigned()->index();
            $table->integer('word_count');
            $table->integer('paragraph_count');
            $table->binary('body')->nullable();
            $table->binary('body_html')->nullable();
            $table->string('name');
            $table->string('reddit_id');
            $table->integer('ups');
            $table->integer('downs');
            $table->integer('article_id')->unsigned()->index();
            $table->integer('gilded');
            $table->bigInteger('created');
            $table->nullableTimestamps();

            $table->unique('reddit_id');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('comments');
    }
}
