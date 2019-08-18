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
})->name('home');

Auth::routes();

Route::post('subscribe', 'SubscribeController@store')->name('subscribe.store');


Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function (){

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('tag', 'TagController');
    Route::resource('category', 'CategoryController');
    Route::resource('post', 'PostController');
    Route::get('pending', 'PostController@pending')->name('pending');
    Route::put('post/{id}/approve', 'PostController@approve')->name('post.approve');
    Route::get('subscriber', 'SubscribeController@index')->name('subscriber');
    Route::delete('subscriber/{id}/delete', 'SubscribeController@destroy')->name('subscriber.destroy');

});

Route::group(['as' => 'author.', 'prefix' => 'author', 'namespace' => 'Author', 'middleware' => ['auth', 'author']], function(){

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('post', 'PostController');
});
