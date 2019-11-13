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
Route::domain(env('POS_URL', 'pos.juztpoint.com'))->group(function ($router) {
	Route::get('/', 'HomeController@pos')->name('pos');
	Route::get('/{any}', 'HomeController@pos')->name('pos');
});

// Static subdomain
Route::domain(env('POS_BACKOFFICE_URL', 'backoffice.juztpoint.com'))->group(function ($router) {
	Route::get('/', 'HomeController@backoffice')->name('backoffice');
	Route::get('/{any}', 'HomeController@backoffice')->name('backoffice');
	Route::get('/services/{any}', 'HomeController@backoffice')->name('backoffice');
	Route::get('/products/{any}', 'HomeController@backoffice')->name('backoffice');
	Route::get('/setting/{any}', 'HomeController@backoffice')->name('backoffice');
});

// Wildcard subdomain
Route::domain(env('APP_URL', 'www.juztpoint.com'))->group(function ($router) {
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