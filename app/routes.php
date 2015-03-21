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

Route::get('test', function()
{

});

Route::get('/',                         'BaseController@index');


Route::get('searches',                  'SearchqueryController@index');
Route::get('search',                    'BaseController@search');

Route::get('subreddits',                'SubredditController@index');
Route::get('sub/{name}',                'SubredditController@show');

Route::get('post/{fullname}',           'ArticleController@show');

Route::get('author/{name}',             'AuthorController@show');

Route::get('domains',                   'BasedomainController@index');
Route::get('domain/{name}',             'BasedomainController@show');


Route::get('admin/errors',              'AdminController@showErrors');

Route::post('webhooks/kimono',          'WebhooksController@kimono');
Route::get('webhooks/last',             'WebhooksController@showLastWebhook');