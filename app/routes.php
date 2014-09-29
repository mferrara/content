<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	$searches = Searchquery::orderBy('id', 'desc')->take(10)->get();

	$subreddits = Subreddit::orderByRaw('rand()')->take(10)->get();

	$authors = Author::orderByRaw('rand()')->take(10)->get();

	return View::make('index')
		->with('searches', 			$searches)
		->with('subreddits', 		$subreddits)
		->with('authors', 			$authors)
		->with('pending_searches', 	Searchquery::where('currently_updating', 1)->orWhere('scraped', 0)->count())
		->with('total_articles', 	Article::count())
		->with('total_authors', 	Author::count())
		->with('total_subreddits', 	Subreddit::count())
		->with('total_queries', 	Searchquery::count())
		->with('total_domains',		Basedomain::count())
		;
});

Route::get('searches', function()
{
	$searches = Searchquery::orderBy('name', 'asc')->paginate(25);

	return View::make('searches')
			->with('searches', $searches);
});

Route::get('search', function()
{
	if(!Input::has('q'))
	{
		return Redirect::to('/');
	}

	$keyword = Input::get('q');

	$usersearch = Usersearch::search($keyword);

	$cache_key = 'searchquery_'.$usersearch->searchquery->id.'_processed_data';
	if(Cache::has($cache_key))
		$aggregate_data = Cache::get($cache_key);
	else
		$aggregate_data = false;

	if($usersearch->searchquery->articles()->count() > 0)
		$articles = $usersearch->searchquery->articles()->orderBy('score', 'DESC')->paginate(25);
	else
		$articles = false;

	if($usersearch->searchquery->currently_updating == 1)
		$currently_updating = true;
	else
		$currently_updating = false;

	return View::make('searchresults')
		->with('usersearch', $usersearch)
		->with('articles', $articles)
		->with('aggregate_data', $aggregate_data)
		->with('currently_updating', $currently_updating);
});

Route::get('post/{fullname}', function($fullname)
{
	$article = Article::where('fullname', $fullname)
		->with(['author', 'subreddit'])
		->first();

	return View::make('post')
		->with('article', $article);
});

Route::get('sub/{name}', function($name)
{
	$subreddit = Subreddit::where('name', $name)
		->first();

	$articles = $subreddit->articles()->orderBy('score', 'DESC')->paginate(25);

	return View::make('subreddit')
		->with('articles', $articles)
		->with('subreddit', $subreddit);
});

Route::get('author/{name}', function($name)
{
	$author = Author::where('name', $name)
		->first();

	$articles = $author->articles()->orderBy('score', 'DESC')->paginate(25);

	return View::make('author')
		->with('articles', $articles)
		->with('author', $author);
});

Route::get('domain/{name}', function($name)
{
	$domain = Basedomain::whereName($name)->first();

	$articles = $domain->articles()->orderBy('score', 'DESC')->paginate(25);

	return View::make('domain')
			->with('domain', $domain)
			->with('articles', $articles);
});