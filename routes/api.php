<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
}); */
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

/*Route::middleware('auth:api')->get('/test', function (Request $request) {
return $request->user();
});*/

//Route::middleware('cors')->get('masters', 'SyncController@all');
//Route::group(['middleware' => ['cors']], function () {
//authToken

//});

Route::group([
	'prefix' => 'auth',
], function () {
	Route::post('pos/register', 'Auth\AuthController@machine');
	Route::post('login', 'Auth\AuthController@login');
	Route::post('register', 'Auth\AuthController@register');
	Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
	Route::post('password/reset/{token}', 'Auth\ForgotPasswordController@showResetForm');
	Route::post('password/reset', 'Auth\ResetPasswordController@reset');

});

// Route::post('user/register', ['as' => 'user.register', 'uses' => 'Logic\UserController@register']);

Route::middleware('auth:api')->group(function () {
	Route::get('me', 'Auth\AuthController@user');
	Route::post('receipt', 'MailController@sendReceipt');
});
