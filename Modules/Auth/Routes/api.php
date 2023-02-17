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

Route::post("auth","AuthController@auth");
Route::post("register","AuthController@register");
Route::get("logout","AuthController@logout");

Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::prefix('access-control')->namespace('AccessControl')->group(function () {

        Route::prefix('user-group')->as('user.group.')->group(function () {
            Route::get('', ['as' => 'index', 'uses' => 'GroupController@index', 'visible' => true, ]);
            Route::post('', ['as' => 'store', 'uses' => 'GroupController@store']);
            Route::match(['get', 'post'], '{usergroup}/permission', ['as' => 'permission', 'uses' => 'GroupController@permission']);
            Route::get('{usergroup}', ['as' => 'show', 'uses' => 'GroupController@show']);
            Route::get('{usergroup}/toggle', ['as' => 'toggle', 'uses' => 'GroupController@toggle']);
            Route::put('{usergroup}', ['as' => 'update', 'uses' => 'GroupController@update']);
        });

        Route::prefix('user')->as('user.')->group(function () {
            Route::get('', ['as' => 'index', 'uses' => 'UserController@index', 'visible' => true]);
            Route::post('', ['as' => 'store', 'uses' => 'UserController@store']);
            Route::get('{user}', ['as' => 'show', 'uses' => 'UserController@show']);
            Route::get('{user}/toggle', ['as' => 'toggle', 'uses' => 'UserController@toggle']);
            Route::put('{user}', ['as' => 'update', 'uses' => 'UserController@update']);
            Route::post('/myprofile', ['as' => 'myprofile', 'uses' => 'UserController@updateProfile']);
        });

    });
});
