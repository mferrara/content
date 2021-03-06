<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddBasedomainIdToArticlesTable extends Migration
{

    /**
         * Run the migrations.
         *
         * @return void
         */
    public function up()
    {
        if (!Schema::hasColumn('articles', 'basedomain_id')) {
            Schema::table('articles', function (Blueprint $table) {
                // Add the new column
                $table->integer('basedomain_id')->indexed()->unsigned()->after('url');
            });
        }

        // Run through the existing records migrating the data to the new column
        App\Article::where('basedomain_id', 0)->chunk(200, function ($articles) {
            foreach ($articles as $article) {
                $article->basedomain_id = Basedomain::findOrCreate($article->base_domain)->id;
                $article->save();
            }
        });

        if (Schema::hasColumn('articles', 'base_domain')) {
            Schema::table('articles', function (Blueprint $table) {

                // Drop old column
                $table->dropColumn('base_domain');
            });
        }
    }


    /**
         * Reverse the migrations.
         *
         * @return void
         */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            // Drop the new column
            $table->dropColumn('basedomain_id');
        });
    }
}
