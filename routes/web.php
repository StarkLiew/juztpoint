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

// Static subdomain
Route::domain('pos.juxtpoint.com')->group(function ($router) {
	Route::get('/', 'HomeController@app')->name('welcome');
	Route::get('/{any}', 'HomeController@app')->name('welcome');
});

// Wildcard subdomain
Route::domain('www.juxtpoint.com')->group(function ($router) {
	Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
	Route::post('register', 'Auth\RegisterController@register');

	Route::get('/', 'HomeController@welcome')->name('welcome');

	Route::middleware('auth:web')->group(function () {
		Route::get('/home', 'HomeController@index')->name('home');
	});

	Route::middleware('auth:web')->group(function () {
		Route::get('/report/{report}', 'ReportController@index')->name('report');
	});

	Route::get('/pos/{vue_capture?}', 'HomeController@pos')->name('pos')->where('vue_capture', '[\/\w\.-]*');

});

// Dev Static subdomain
Route::domain('localhost:3000')->group(function ($router) {
	Route::get('/', 'HomeController@app')->name('welcome');
	Route::get('/{any}', 'HomeController@app')->name('welcome');
});