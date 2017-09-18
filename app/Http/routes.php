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

Route::get('test', function () {
});

Route::get('/', 'HomeController@index');

Route::get('pending', 'SearchqueryController@pending');
Route::get('searches', 'SearchqueryController@index');
Route::get('search', 'HomeController@search');

Route::get('subreddits', 'SubredditController@index');
Route::get('sub/{name}', 'SubredditController@show');

Route::get('post/{fullname}', 'ArticleController@show');

Route::get('authors', 'AuthorController@index');
Route::get('author/{name}', 'AuthorController@show');

Route::get('domains', 'BasedomainController@index');
Route::get('domain/{name}', 'BasedomainController@show');

Route::get('admin', 'AdminController@index');
Route::get('admin/searches', 'AdminController@searchqueries');
Route::get('admin/authors', 'AdminController@authors');
Route::get('admin/domains', 'AdminController@domains');
Route::get('admin/subreddits', 'AdminController@subreddits');
Route::get('admin/errors', 'AdminController@showErrors');

Route::post('webhooks/kimono', 'WebhooksController@kimono');
Route::get('webhooks/last', 'WebhooksController@showLastWebhook');

Route::get('api/search', 'ApiController@search');
