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
        Route::prefix('purchaseorders')->namespace('PurchaseOrders')->group(function () {
            Route::prefix('purchaseorders')->as('purchaseorders.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'PurchaseOrderController@index', 'visible' => true,'vue'=>'/bakeryManager/purchase/list','custom_label'=>'Todays Purchase']);
                Route::get('{purchaseOrder}/show', ['as' => 'show', 'uses' => 'PurchaseOrderController@show']);
                Route::get('create', ['as' => 'create', 'uses' => 'PurchaseOrderController@create','visible' => true]);
                Route::post('store', ['as' => 'store', 'uses' => 'PurchaseOrderController@store' ,'vue'=>'/bakeryManager/purchase/add','custom_label'=>'Create New Material Purchase']);
                Route::get('{purchaseOrder}/remove', ['as' => 'destroy', 'uses' => 'PurchaseOrderController@destroy','vue'=>'delete-material-purchase','custom_label'=>'Delete Purchase']);

                Route::get('{purchaseOrder}/edit', ['as' => 'edit', 'uses' => 'PurchaseOrderController@edit']);
                Route::get('{purchaseOrder}/markAsComplete', ['as' => 'markAsComplete', 'uses' => 'PurchaseOrderController@markAsComplete', 'custom_label'=>'Complete Purchase Order','vue'=>'update-purchase','custom_label'=>'Complete Purchase']);
                Route::put('{purchaseOrder}/update', ['as' => 'update', 'uses' => 'PurchaseOrderController@update','vue'=>'update-purchase','custom_label'=>'Update Purchase']);
                Route::post('/custom', ['as' => 'custom', 'uses' => 'PurchaseOrderController@custom']);
            });
        });
    });
});
