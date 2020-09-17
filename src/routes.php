<?php

use tizis\laraComments\Http\Controllers\CommentsController;
use tizis\laraComments\Http\Controllers\VoteController;

if (config('comments.route.root') !== null) {
    Route::group(['prefix' => config('comments.route.root')], static function () {
        Route::group(['prefix' => config('comments.route.group'), 'as' => 'comments.',], static function () {
            Route::get('/', [CommentsController::class, 'get'])->name('get');
            Route::post('/', [CommentsController::class, 'store'])->name('store');
            Route::delete('/{comment}', [CommentsController::class, 'destroy'])->name('delete');
            Route::put('/{comment}', [CommentsController::class, 'update'])->name('update');
            Route::get('/{comment}', [CommentsController::class, 'show']);
            Route::post('/{comment}', [CommentsController::class, 'reply'])->name('reply');
            Route::post('/{comment}/vote', [VoteController::class, 'vote'])->name('vote');
        });
    });
}


