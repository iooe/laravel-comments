<?php

Route::group(['prefix' => 'api'], function () {
    /**
     * Comments
     */
    Route::group(['prefix' => 'comments'], function () {
        Route::get('/', '\tizis\laraComments\Http\Controllers\CommentsController@get');
        Route::post('/', '\tizis\laraComments\Http\Controllers\CommentsController@store');
        Route::delete('/{comment}', '\tizis\laraComments\Http\Controllers\CommentsController@destroy');
        Route::put('/{comment}', '\tizis\laraComments\Http\Controllers\CommentsController@update');
        Route::post('/{comment}', '\tizis\laraComments\Http\Controllers\CommentsController@reply');
    });
});

