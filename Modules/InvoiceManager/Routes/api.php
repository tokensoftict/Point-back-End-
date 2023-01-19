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

        Route::prefix('InvoiceManager')->namespace('InvoiceManager')->group(function () {

            Route::prefix('invoice')->as('invoice.')->group(function () {

                Route::get('', ['as' => 'index', 'uses' => 'InvoiceManagerController@index', 'visible' => true]);
                Route::get('/draft', ['as' => 'draft', 'uses' => 'InvoiceManagerController@draft', 'visible' => true]);
                Route::get('/complete', ['as' => 'complete', 'uses' => 'InvoiceManagerController@complete', 'visible' => true]);
                Route::post('', ['as' => 'store', 'uses' => 'InvoiceManagerController@store']);
                Route::get('{invoice}/show', ['as' => 'show', 'uses' => 'InvoiceManagerController@show']);
                Route::get('{invoice}/destroy', ['as' => 'destroy', 'uses' => 'InvoiceManagerController@show']);
                Route::put('{invoice}', ['as' => 'update', 'uses' => 'InvoiceManagerController@update']);

            });

        });
    });
});
