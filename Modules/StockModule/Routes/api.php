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
        Route::prefix('stockmanager')->namespace('StockManager')->group(function () {

            Route::prefix('stock')->as('stock.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'StockController@index', 'visible' => true,'vue'=>'/stock/new','custom_label'=>'Create New Stock']);
                Route::get('available', ['as' => 'available', 'uses' => 'StockController@available','visible' => true,'vue'=>'/stock/list','custom_label'=>'List Available Stock']);
                //Route::get('expired', ['as' => 'expired', 'uses' => 'StockController@expired','visible' => true]);
                Route::get('disable', ['as' => 'disable', 'uses' => 'StockController@disabled','visible' => true,'vue'=>'/stock/disabled','custom_label'=>'List Disabled Stock']);
               // Route::get('export', ['as' => 'export', 'uses' => 'StockController@export']);
                //Route::get('create', ['as' => 'create', 'uses' => 'StockController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'StockController@store']);
                Route::get('{stock}/show', ['as' => 'show', 'uses' => 'StockController@show']);
                Route::get('{stock}/edit', ['as' => 'edit', 'uses' => 'StockController@edit','vue'=>'edit-stock','custom_label'=>'Edit Stock']);
                Route::put('{stock}', ['as' => 'update', 'uses' => 'StockController@update']);
                Route::get('{stock}/toggle', ['as' => 'toggle', 'uses' => 'StockController@toggle','vue'=>'toggle-stock','custom_label'=>'Toggle Stock']);
                Route::get('requesites', ['as' => 'requesites', 'uses' => 'StockController@requesites']);

                Route::post('/find', ['as' => 'find', 'uses' => 'StockController@find']);
                Route::post('/findAvailable', ['as' => 'findAvailable', 'uses' => 'StockController@findAvailable']);
                Route::post('/receive_stock', ['as' => 'receive_stock', 'uses' => 'StockController@receive_stock']);

            });




        });
    });
});
