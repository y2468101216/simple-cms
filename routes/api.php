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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/order', ['as' => 'api.order.store', 'uses' => '\App\Http\Controllers\OrderController@store']);
    Route::delete('/order/{id}', ['as' => 'api.order.delete', 'uses' => '\App\Http\Controllers\OrderController@delete']);
});

Route::post('order/callback', ['as' => 'api.order.callback', 'uses' => '\App\Http\Controllers\OrderController@callback']);
