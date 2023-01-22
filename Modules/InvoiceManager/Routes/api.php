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

                //invoice report start here
                Route::post('/daily', ['as' => 'daily', 'uses' => 'InvoiceManagerController@dailyreport', 'visible' => true]);
                Route::post('/weekly', ['as' => 'weekly', 'uses' => 'InvoiceManagerController@weeklyreport', 'visible' => true]);
                Route::post('/monthly', ['as' => 'monthly', 'uses' => 'InvoiceManagerController@monthlyreport', 'visible' => true]);
                Route::post('/by_user', ['as' => 'by_user', 'uses' => 'InvoiceManagerController@report', 'visible' => true]);
                Route::post('/by_customer', ['as' => 'by_customer', 'uses' => 'InvoiceManagerController@report', 'visible' => true]);
            });

        });
    });
});
