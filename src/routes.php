<?php

$namespace = config('field-file.routes.namespace', 'Fomvasss\FieldFile\Http\Controllers');
$middleware = config('field-file.routes.middlewares', ['web']);
$prefix = config('field-file.routes.prefix', 'file');
$as = config('field-file.routes.as', 'file.');

Route::group(compact('middleware', 'prefix', 'as', 'namespace'), function () {
    Route::group(['prefix' => 'document', 'as' => 'document.'], function () {
        Route::post('/upload', ['uses' => 'DocumentController@upload', 'as' => 'upload']);
        Route::post('/upload-multiple', ['uses' => 'DocumentController@uploadMultiple', 'as' => 'upload.multiple']);
        Route::get('/show/{id}', ['uses' => 'DocumentController@show', 'as' => 'upload.show']);
        Route::get('/get-url/{id}', ['uses' => 'DocumentController@getUrl', 'as' => 'get.url']);
        Route::get('/get-file/{id}', ['uses' => 'DocumentController@getFile', 'as' => 'get.file']);
        Route::delete('/delete/{id}', ['uses' => 'DocumentController@delete', 'as' => 'delete']);
    });

    Route::group(['prefix' => 'image', 'as' => 'image.'], function () {
        Route::post('/upload', ['uses' => 'ImageController@upload', 'as' => 'upload']);
        Route::post('/upload-multiple', ['uses' => 'ImageController@uploadMultiple', 'as' => 'upload.multiple']);
        Route::get('/show/{id}', ['uses' => 'ImageController@show', 'as' => 'upload.show']);
        Route::get('/get-url/{id}', ['uses' => 'ImageController@getUrl', 'as' => 'get.url']);
        Route::get('/get-file/{id}', ['uses' => 'ImageController@getFile', 'as' => 'get.file']);
        Route::get('/delete/{id}', ['uses' => 'ImageController@delete', 'as' => 'delete']);
    });
});
