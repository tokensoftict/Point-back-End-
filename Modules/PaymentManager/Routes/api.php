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

        Route::prefix('PaymentManager')->namespace('PaymentManager')->group(function () {

            Route::prefix('payment')->as('payment.')->group(function () {

                Route::get('', ['as' => 'index', 'uses' => 'PaymentManagerController@index', 'visible' => true]);
                Route::get('{id}/invoice', ['as' => 'invoice', 'uses' => 'PaymentManagerController@invoice', 'visible' => true]);
                Route::get('/payment_by_method', ['as' => 'payment_by_method', 'uses' => 'PaymentManagerController@payment_by_method', 'visible' => true]);
                Route::post('storeCreditPayment', ['as' => 'storeCreditPayment', 'uses' => 'PaymentManagerController@storeCreditPayment']);
                Route::post('', ['as' => 'store', 'uses' => 'PaymentManagerController@store']);
                Route::post('/custom', ['as' => 'custom', 'uses' => 'PaymentManagerController@custom']);
                Route::post('/payment_by_method_custom', ['as' => 'payment_by_method_custom', 'uses' => 'PaymentManagerController@payment_by_method_custom']);
                Route::put('{invoice}', ['as' => 'update', 'uses' => 'PaymentManagerController@update']);
            });
        });
    });
});
