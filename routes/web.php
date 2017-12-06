<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/threads', 'ThreadsController@store');
Route::get('/threads/create', 'ThreadsController@create');
Route::get('/threads/{channel?}', 'ThreadsController@index');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadsSubscriptionController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadsSubscriptionController@destroy')->middleware('auth');

Route::get('/threads/{channel}/{thread}/replies', 'ThreadsRepliesController@index');
Route::post('/threads/{channel}/{thread}/replies', 'ThreadsRepliesController@store');
Route::patch('/replies/{reply}', 'ThreadsRepliesController@update');
Route::delete('/replies/{reply}', 'ThreadsRepliesController@destroy');
Route::delete('/threads/{channel}/{thread}/replies/{reply}', 'ThreadsRepliesController@destroy')
      ->name('reply.delete');
//TODO fix this authorization with gate because it is not working

Route::delete('/threads/{thread}', 'ThreadsController@destroy')->name('thread.delete')->middleware('can:delete,thread');

Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/user/{user}/profile', 'UserProfileController@index')->name('profile');
Route::get('/profile/{user}/notifications', 'UserNotificationsController@index');
Route::post('/profile/{user}/notifications/{notification}', 'UserNotificationsController@store');

Route::get('/api/users', 'Api\UsersController@index');
Route::get('/register/confirm', 'Api\UsersConfirmationController@index');