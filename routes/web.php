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


// Route::get('/new', function () {
//     return view('new');
// })->name('new');;

// Route::get('/', 'DocumentsController@index')->name('index');
// Route::post('/new', 'DocumentsController@store');

Route::get('/', 'DocumentsController@index')->name('index');
Route::resource('documents', 'DocumentsController');