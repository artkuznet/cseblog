<?php

//Route::group(['middleware' => []], function () {

    Route::get('/news', 'NewsController@Get');
    Route::get('/news/{slug}', 'NewsController@Get');

    Route::post('/news', 'NewsController@Post');

    Route::delete('/news/{id}','NewsController@Delete');


    Route::get('/images', 'ImagesController@Get');
    Route::get('/images/{guid}', 'ImagesController@Get');

    Route::delete('/images/{guid}','ImagesController@Delete');



    Route::post('/images', 'ImagesController@Post');
//});
