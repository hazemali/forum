<?php

// index
Route::get('/', 'ThreadsController@index');


// routes
Auth::routes();

// default home
Route::get('/home', 'HomeController@index');


// App

// profiles
Route::get('profiles/{user}', 'ProfilesController@show')->name('profiles.show');
// notifications
Route::delete('notifications/{notification}', 'NotificationsController@destroy');
Route::get('notifications', 'NotificationsController@index');


// threads
Route::get('threads/create', 'ThreadsController@create')->name('threads.create');
Route::get('threads', 'ThreadsController@index')->name('threads.index');
Route::post('/threads', 'ThreadsController@store')->name('threads.store');
Route::get('threads/{channel}', 'ThreadsController@index');
Route::get('threads/{channel}/{thread}', 'ThreadsController@show')->name('threads.show');
// thread subscriptions
Route::delete('threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::post('threads/{channel}/{thread}/subscriptions', 'ThreadsSubscriptionsController@store');
Route::delete('threads/{channel}/{thread}/subscriptions', 'ThreadsSubscriptionsController@destroy');

// replies
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');
Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::delete('replies/{reply}', 'ReplyController@destroy');
Route::patch('replies/{reply}', 'ReplyController@update');

// favorites
Route::post('replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('replies/{reply}/favorites', 'FavoritesController@destroy');
