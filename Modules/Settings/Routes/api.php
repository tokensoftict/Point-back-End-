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

        Route::prefix('settings')->namespace('Settings')->group(function () {

            Route::prefix('store')->as('store.')->group(function () {
                Route::get('', ['as' => 'view', 'uses' => 'StoreSettings@show', 'visible' => true]);
                Route::put('update', ['as' => 'update', 'uses' => 'StoreSettings@update']);
            });


            Route::prefix('bank')->as('bank.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'BankController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'BankController@listAll']);
                Route::get('commercial', ['as' => 'commercial', 'uses' => 'BankController@listAllCommercial','lable'=>'List all Commercial Banks']);
                Route::get('create', ['as' => 'create', 'uses' => 'BankController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'BankController@store']);
                Route::get('{bankAccount}', ['as' => 'show', 'uses' => 'BankController@show']);
                Route::get('{bankAccount}/edit', ['as' => 'edit', 'uses' => 'BankController@edit']);
                Route::get('{bankAccount}/toggle', ['as' => 'toggle', 'uses' => 'BankController@toggle']);
                Route::put('{bankAccount}', ['as' => 'update', 'uses' => 'BankController@update']);
                Route::delete('{bankAccount}', ['as' => 'destroy', 'uses' => 'BankController@destroy']);
            });

            Route::prefix('category')->as('category.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'CategoryController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'CategoryController@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'CategoryController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'CategoryController@store']);
                Route::get('{productCategory}', ['as' => 'show', 'uses' => 'CategoryController@show']);
                Route::get('{productCategory}/edit', ['as' => 'edit', 'uses' => 'CategoryController@edit']);
                Route::get('{productCategory}/toggle', ['as' => 'toggle', 'uses' => 'CategoryController@toggle']);
                Route::put('{productCategory}', ['as' => 'update', 'uses' => 'CategoryController@update']);
                Route::delete('{productCategory}', ['as' => 'destroy', 'uses' => 'CategoryController@destroy']);
            });


            Route::prefix('manufacturer')->as('manufacturer.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'ManufacturerController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'ManufacturerController@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'ManufacturerController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'ManufacturerController@store']);
                Route::get('{manufacturer}', ['as' => 'show', 'uses' => 'ManufacturerController@show']);
                Route::get('{manufacturer}/edit', ['as' => 'edit', 'uses' => 'ManufacturerController@edit']);
                Route::get('{manufacturer}/toggle', ['as' => 'toggle', 'uses' => 'ManufacturerController@toggle']);
                Route::put('{manufacturer}', ['as' => 'update', 'uses' => 'ManufacturerController@update']);
                Route::delete('{manufacturer}', ['as' => 'destroy', 'uses' => 'ManufacturerController@destroy']);
            });

            Route::prefix('payment_method')->as('payment_method.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'PaymentMethodController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'PaymentMethodController@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'PaymentMethodController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'PaymentMethodController@store']);
                Route::get('{paymentMethod}', ['as' => 'show', 'uses' => 'PaymentMethodController@show']);
                Route::get('{paymentMethod}/edit', ['as' => 'edit', 'uses' => 'PaymentMethodController@edit']);
                Route::get('{paymentMethod}/toggle', ['as' => 'toggle', 'uses' => 'PaymentMethodController@toggle']);
                Route::put('{paymentMethod}', ['as' => 'update', 'uses' => 'PaymentMethodController@update']);
                Route::delete('{paymentMethod}', ['as' => 'destroy', 'uses' => 'PaymentMethodController@destroy']);
            });

            Route::prefix('supplier')->as('supplier.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'SupplierController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'SupplierController@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'SupplierController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'SupplierController@store']);
                Route::get('{supplier}', ['as' => 'show', 'uses' => 'SupplierController@show']);
                Route::get('{supplier}/edit', ['as' => 'edit', 'uses' => 'SupplierController@edit']);
                Route::get('{supplier}/toggle', ['as' => 'toggle', 'uses' => 'SupplierController@toggle']);
                Route::put('{supplier}', ['as' => 'update', 'uses' => 'SupplierController@update']);
                Route::delete('{supplier}', ['as' => 'destroy', 'uses' => 'SupplierController@destroy']);
            });

            Route::prefix('expenses_type')->as('expenses_type.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'ExpensesTypeController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'ExpensesTypeController@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'ExpensesTypeController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'ExpensesTypeController@store']);
                Route::get('{expensesType}', ['as' => 'show', 'uses' => 'ExpensesTypeController@show']);
                Route::get('{expensesType}/edit', ['as' => 'edit', 'uses' => 'ExpensesTypeController@edit']);
                Route::get('{expensesType}/toggle', ['as' => 'toggle', 'uses' => 'ExpensesTypeController@toggle']);
                Route::put('{expensesType}', ['as' => 'update', 'uses' => 'ExpensesTypeController@update']);
                Route::delete('{expensesType}', ['as' => 'destroy', 'uses' => 'ExpensesTypeController@destroy']);
            });

        });

    });

});
