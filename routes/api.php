<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


//Route::group(['middleware'=>['apiToken'], 'as'=>'api.'], function() {
//    Route::post('users/add','\App\Http\Controllers\Api\UserApiController@add_users')->name('users.add');
//
//});



Route::group([
    'middleware' => 'api',
    'namespace' => '\App\Http\Controllers\Api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('sign', 'AuthController@signup');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

