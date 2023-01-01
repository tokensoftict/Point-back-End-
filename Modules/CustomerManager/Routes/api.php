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

Route::prefix('customerManager')->namespace('CustomerManager')->group(function () {

    Route::prefix('customer')->as('customer.')->group(function () {
        Route::get('', ['as' => 'index', 'uses' => 'CustomerController@index', 'visible' => true]);
        Route::get('list', ['as' => 'list', 'uses' => 'CustomerController@list_all']);
        Route::get('create', ['as' => 'create', 'uses' => 'CustomerController@create', 'visible' => true]);
        Route::post('', ['as' => 'store', 'uses' => 'CustomerController@store']);
        Route::get('{customer}/show', ['as' => 'show', 'uses' => 'CustomerController@show']);
        Route::get('{customer}/edit', ['as' => 'edit', 'uses' => 'CustomerController@edit']);
        Route::put('{customer}', ['as' => 'update', 'uses' => 'CustomerController@update']);
    });

});
