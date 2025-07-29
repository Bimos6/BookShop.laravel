<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AdminModeController;

Route::get('/', function () {
    return redirect()->route('books.index');
});

Route::resource('books', BookController::class);
Route::resource('authors', AuthorController::class)->except(['show']);

Route::prefix('admin')->group(function () {
    Route::resource('admin', AdminModeController::class);
    
    Route::post('/toggle-admin-mode', [AdminModeController::class, 'toggle'])
        ->name('books.toggle-admin-mode');
        
    Route::get('/check-admin-mode', [AdminModeController::class, 'check'])
        ->name('books.check-admin-mode');
});