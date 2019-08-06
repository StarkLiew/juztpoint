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
// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

Route::get('/', 'HomeController@welcome')->name('welcome');
Route::get('/pos', 'HomeController@pos')->name('pos');

Route::middleware('auth:web')->group(function () {
	Route::get('/home', 'HomeController@index')->name('home');
});
