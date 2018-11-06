<?php

Route::post('comments', '\tizis\laraComments\Controllers\CommentsController@store');
Route::delete('comments/{comment}', '\tizis\laraComments\Controllers\CommentsController@destroy');
Route::put('comments/{comment}', '\tizis\laraComments\Controllers\CommentsController@update');
Route::post('comments/{comment}', '\tizis\laraComments\Controllers\CommentsController@reply');