<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('authors.index');
});

Route::resource('authors', AuthorController::class);
Route::resource('books', BookController::class);
