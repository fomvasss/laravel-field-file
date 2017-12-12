<?php

$namespace = config('field_file.routes.namespace', 'Fomvasss\FieldFile\Http\Controllers');
$middleware = config('field_file.routes.middlewares', ['web']);
$prefix = config('field_file.routes.prefix', 'file');
$as = config('field_file.routes.as', 'file.');

Route::group(compact('middleware', 'prefix', 'as', 'namespace'), function () {
    Route::group(['prefix' => 'document', 'as' => 'document.'], function () {
        Route::post('/upload', ['uses' => 'DocumentController@upload', 'as' => 'upload']);
        Route::post('/upload-multiple', ['uses' => 'DocumentController@uploadMultiple', 'as' => 'upload.multiple']);
        Route::get('/get-file/{id}', ['uses' => 'DocumentController@getFile', 'as' => 'get.file']);
        Route::delete('/delete/{id}', ['uses' => 'DocumentController@delete', 'as' => 'delete']);
    });

    Route::group(['prefix' => 'image', 'as' => 'image.'], function () {
        Route::post('/upload', ['uses' => 'ImageController@upload', 'as' => 'upload']);
        Route::post('/upload-multiple', ['uses' => 'ImageController@uploadMultiple', 'as' => 'upload.multiple']);
        Route::get('/get-file/{id}', ['uses' => 'ImageController@getFile', 'as' => 'get.file']);
        Route::get('/delete/{id}', ['uses' => 'ImageController@delete', 'as' => 'delete']);
    });
});
