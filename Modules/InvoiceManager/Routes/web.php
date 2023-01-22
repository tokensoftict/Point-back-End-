<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('InvoiceManager')->namespace('InvoiceManager')->group(function () {

    Route::prefix('invoice')->as('invoice.')->group(function () {
        Route::get('{id}/pos_print', ['as' => 'pos_print', 'uses' => 'InvoiceManagerController@print_pos' ]);
        Route::get('{id}/print_afour', ['as' => 'print_afour', 'uses' => 'InvoiceManagerController@print_afour']);
        Route::get('{id}/print_way_bill', ['as' => 'print_way_bill', 'uses' => 'InvoiceManagerController@print_way_bill']);
    });

});
