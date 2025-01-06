<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/posts', \App\Http\Controllers\PostController::class);

Route::post('posts/{post}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');