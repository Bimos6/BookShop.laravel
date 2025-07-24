<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;

Route::get('/', function () {
    return redirect()->route('books.index');
});

Route::resource('books', BookController::class);

Route::resource('authors', AuthorController::class)->except(['show']);

Route::post('/books/toggle-admin-mode', [BookController::class, 'toggleAdminMode'])->name('books.toggle-admin-mode');

Route::get('/books/check-admin-mode', [BookController::class, 'checkAdminMode'])->name('books.check-admin-mode');