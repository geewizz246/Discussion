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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Discussion Search
Route::prefix('/discussion')->group(function() {
    // Search All Discussions
    Route::get('/search', 'DiscussionController@search')->name('discussion.search');
});

// RESTful resource\
Route::resource('discussion', 'DiscussionController');

Route::resource('post', 'PostController');

// User Profile Routes
Route::prefix('/{username}')->group(function() {
    // Route::get('/', 'HomeController@profile')->name('user.profile');
    Route::get('/', 'ProfileController@profile')->name('user.profile');

    // Search User Discussions
    Route::get('/discussion/search', 'ProfileController@searchUserDiscussions')->name('user.discussion.search');
});

