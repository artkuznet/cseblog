<?php

Route::get('/news', 'NewsController@Get');
Route::get('/news/{slug}', 'NewsController@Get');
Route::post('/news', 'NewsController@Post');

Route::get('/images', 'ImagesController@Get');
Route::get('/images/{guid}', 'ImagesController@Get');
Route::post('/images', 'ImagesController@Post');

Route::group(['middleware' => ['admin']], function () {
    Route::put('/news/{id}','NewsController@Update');
    Route::delete('/news/{id}','NewsController@Delete');
    Route::post('/images/{guid}', 'ImagesController@Update'); // через x-www-form-urlencoded файл не передать, поэтому post
    Route::delete('/images/{guid}','ImagesController@Delete');
});