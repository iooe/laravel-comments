<?php

Route::get('comments', '\tizis\laraComments\Http\Controllers\CommentsController@get');
Route::post('comments', '\tizis\laraComments\Http\Controllers\CommentsController@store');
Route::delete('comments/{comment}', '\tizis\laraComments\Http\Controllers\CommentsController@destroy');
Route::put('comments/{comment}', '\tizis\laraComments\Http\Controllers\CommentsController@update');
Route::post('comments/{comment}', '\tizis\laraComments\Http\Controllers\CommentsController@reply');