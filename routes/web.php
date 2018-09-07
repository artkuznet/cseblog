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

Route::get('/', function () {
    return view('welcome');
});


//Route::group(['middleware' => []], function () {

    Route::get('/news/{slug}', 'NewsController@Get');

    Route::get('/news', 'NewsController@Get');

    Route::post('/news', 'NewsController@Post');


    Route::get('/images', 'ImagesController@Get');

    Route::post('/images', 'ImagesController@Post');
//});
