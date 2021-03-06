<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::auth();
    Route::get('/create', 'DashboardController@getCreate');
    Route::post('/create', 'DashboardController@postCreate');
    Route::get('/{mp_id}/menu', 'DashboardController@getMenu');
    Route::post('/api/{mp_id}/menu/save', 'ApiController@saveMenu');
    Route::get('/api/{mp_id}/menu/pull', 'ApiController@pullMenu');

    Route::get('/{mp_id}/news', 'DashboardController@getNews');
    Route::get('/{mp_id}/news/{news_id}/edit', 'DashboardController@editNews');
    Route::get('/api/{mp_id}/news/pull', 'ApiController@pullNews');
    Route::get('/api/article/{article_id}', 'ApiController@getArticle');

    Route::get('/', 'DashboardController@index');

});
