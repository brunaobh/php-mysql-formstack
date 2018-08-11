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

// API v1


// V1
Route::group(['prefix' => 'v1/documents', 'namespace' => 'Api\v1'], function () { 
	Route::get('/', 'DocumentsApiController@index');
	Route::get('/{id}', 'DocumentsApiController@index@show');
	Route::post('/', 'DocumentsApiController@store');
	Route::put('/{id}', 'DocumentsApiController@update');
	Route::delete('/{id}', 'DocumentsApiController@delete');
});