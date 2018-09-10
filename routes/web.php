<?php

//Route::group(['middleware' => []], function () {

    Route::get('/news/{slug}', 'NewsController@Get');
    Route::get('/news', 'NewsController@Get');
    Route::post('/news', 'NewsController@Post');
    Route::put('/news/{id}','NewsController@Update');
    Route::delete('/news/{id}','NewsController@Delete');

    Route::get('/images', 'ImagesController@Get');
    Route::get('/images/{guid}', 'ImagesController@Get');
    Route::post('/images', 'ImagesController@Post');
    Route::post('/images/{guid}', 'ImagesController@Update');
    Route::delete('/images/{guid}','ImagesController@Delete');

//});
