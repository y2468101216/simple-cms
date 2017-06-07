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

Route::get('/complete', ['as' => 'callback.complete'], function () {
    return view('order.complete');
});

Route::get('/complete_error', ['as' => 'callback.complete_error'], function () {
    return view('order.complete_error');
});
