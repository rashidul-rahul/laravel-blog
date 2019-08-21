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

Route::get('/', 'HomeController@index')->name('home');
Route::get('posts','PostController@index')->name('post.index');
Route::get('post/{slug}','PostController@details')->name('post.details');

Auth::routes();

Route::post('subscribe', 'SubscribeController@store')->name('subscribe.store');

Route::group(['middleware' => ['auth']], function (){
    Route::post('favorite/{post}/add', 'FavoriteController@addFavorite')->name('post.favorite');
    Route::post('comment/store', 'CommentController@store')->name('comment.store');
});


Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function (){

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('tag', 'TagController');
    Route::resource('category', 'CategoryController');
    Route::resource('post', 'PostController');
    Route::get('pending', 'PostController@pending')->name('pending');
    Route::put('post/{id}/approve', 'PostController@approve')->name('post.approve');
    Route::get('subscriber', 'SubscribeController@index')->name('subscriber');
    Route::delete('subscriber/{id}/delete', 'SubscribeController@destroy')->name('subscriber.destroy');
    Route::get('settings', 'SettingsController@index')->name('settings');
    Route::put('profile-update', 'SettingsController@profileUpdate')->name('profile.update');
    Route::post('password-change', 'SettingsController@changePassword')->name('password.change');
    Route::get('favorite-post', 'FavoriteController@index')->name('favorite.post');
    Route::post('remove-favorite/{id}/post', 'FavoriteController@removeFavorite')->name('remove.favorite.post');
    Route::get('comment', 'CommentController@index')->name('comment.index');
    Route::delete('comment/{id}/delete', 'CommentController@destroy')->name('comment.destroy');

});

Route::group(['as' => 'author.', 'prefix' => 'author', 'namespace' => 'Author', 'middleware' => ['auth', 'author']], function(){

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('post', 'PostController');
    Route::get('settings', 'SettingsController@index')->name('settings');
    Route::put('profile-update', 'SettingsController@profileUpdate')->name('profile.update');
    Route::post('password-change', 'SettingsController@changePassword')->name('password.change');
    Route::get('favorite/post', 'FavoriteController@index')->name('favorite.post');
    Route::post('remove-favorite/{id}/post', 'FavoriteController@removeFavorite')->name('remove.favorite.post');
    Route::get('comment', 'CommentController@index')->name('comment.index');
    Route::delete('comment/{id}/delete', 'CommentController@destroy')->name('comment.destroy');
});
