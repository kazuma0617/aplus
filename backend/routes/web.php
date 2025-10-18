<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

Route::get('/', function () {
    return view('top');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/create', [ArticleController::class, 'create'])->name('create');
Route::post('/store', [ArticleController::class, 'store'])->name('store');
Route::get('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('edit');
Route::put('/articles/{id}', [ArticleController::class, 'update'])->name('update');
Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->name('destroy');




Route::get('/articles', [ArticleController::class, 'index'])->name('index');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('show');
