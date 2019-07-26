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

Route::post('user/login', 'Auth\LoginController@login');
Route::post('/user/register', 'Auth\RegisterController@register');
// Route::post('user/register', ['as' => 'user.register', 'uses' => 'Logic\UserController@register']);

Route::middleware('auth:api')->group(function () {

});
