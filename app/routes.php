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
	$searches = Usersearch::orderBy('id', 'desc')->take(10)->get();

	return View::make('index')
		->with('searches', $searches);
});

Route::get('search', function()
{
	if(!Input::has('q'))
	{
		return Redirect::to('/');
	}

	$keyword = Input::get('q');

	$query = Searchquery::where('name', $keyword)->first();
	if($query == null)
		$query = Searchquery::create(['name' => $keyword]);

	$search = Usersearch::search($query);

	$articles = $search->searchquery->articles()->orderBy('score', 'DESC')->paginate(25);

	return View::make('searchresults')
		->with('usersearch', $search)
		->with('articles', $articles);
});

Route::get('post/{fullname}', function($fullname)
{
	$article = Article::where('fullname', $fullname)
		->with(['author', 'subreddit'])
		->first();

	return $article;
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