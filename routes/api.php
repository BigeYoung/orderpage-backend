<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/order', 'App\Http\Controllers\OrderController@add');
Route::get('/count', 'App\Http\Controllers\ProductController@count');
Route::get('/products/process', 'App\Http\Controllers\ProductController@processList');
Route::get('/products/done', 'App\Http\Controllers\ProductController@doneList');
Route::get('/products/info/{product_guid}', 'App\Http\Controllers\ProductController@info');
Route::get('/products/pallet/{product_guid}', 'App\Http\Controllers\ProductController@pallet');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
