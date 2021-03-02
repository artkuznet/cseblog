<?php

Route::get('/news', 'NewsController@get');
Route::get('/news/{slug}', 'NewsController@get');
Route::post('/news', 'NewsController@post');

Route::get('/images', 'ImagesController@get');
Route::get('/images/{guid}', 'ImagesController@get');
Route::post('/images', 'ImagesController@post');

Route::group(['middleware' => ['admin']], function () {
    Route::put('/news/{id}', 'NewsController@update');
    Route::delete('/news/{id}', 'NewsController@delete');
    Route::post('/images/{guid}', 'ImagesController@update'); // через x-www-form-urlencoded файл не передать, поэтому post
    Route::delete('/images/{guid}', 'ImagesController@delete');
});