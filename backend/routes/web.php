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


Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/create', [ArticleController::class, 'create']);
Route::post('/articles', [ArticleController::class, 'store']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);
Route::get('/articles/{id}/edit', [ArticleController::class, 'edit']);
Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
