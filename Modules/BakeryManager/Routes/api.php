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

Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::middleware(['permit.task'])->group(function () {

        Route::prefix('bakeryManager')->namespace('BakeryManager')->group(function () {

            Route::prefix('rawmaterial')->as('rawmaterial.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'RawMaterialController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'RawMaterialController@listAll']);
                Route::get('materialtype', ['as' => 'materialtype', 'uses' => 'RawMaterialController@listMaterialType','label'=>'List all Material Type']);
                Route::get('create', ['as' => 'create', 'uses' => 'RawMaterialController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'RawMaterialController@store']);
                Route::post('/find', ['as' => 'find', 'uses' => 'RawMaterialController@find']);
                Route::post('/findAvailable', ['as' => 'findAvailable', 'uses' => 'RawMaterialController@findAvailable']);
                Route::get('{rawmaterial}', ['as' => 'show', 'uses' => 'RawMaterialController@show']);
                Route::get('{rawmaterial}/toggle', ['as' => 'toggle', 'uses' => 'RawMaterialController@toggle']);
                Route::put('{rawmaterial}', ['as' => 'update', 'uses' => 'RawMaterialController@update']);
                Route::delete('{rawmaterial}', ['as' => 'destroy', 'uses' => 'RawMaterialController@destroy']);
            });

            Route::prefix('production')->as('production.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'ProductionController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'ProductionController@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'ProductionController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'ProductionController@store']);
                Route::get('{bakeryproduction}/show', ['as' => 'show', 'uses' => 'ProductionController@show']);
                Route::put('{bakeryproduction}/update', ['as' => 'update', 'uses' => 'ProductionController@update']);
                Route::put('{bakeryproduction}/complete', ['as' => 'complete', 'uses' => 'ProductionController@complete']);
                Route::delete('{bakeryproduction}', ['as' => 'destroy', 'uses' => 'ProductionController@destroy']);
            });

            Route::prefix('transfer')->as('transfer.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'MaterialTransferController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'MaterialTransferController@listAll']);
                Route::get('{bakeryproduction}/show', ['as' => 'show', 'uses' => 'MaterialTransferController@show']);
                Route::get('{bakeryproduction}/accept', ['as' => 'accept', 'uses' => 'MaterialTransferController@accept']);
                Route::get('{bakeryproduction}/decline', ['as' => 'decline', 'uses' => 'MaterialTransferController@decline']);
            });

        });

    });

});
