<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QiitaController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use App\Models\Article;

// 認証
Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register.show');
Route::post('/register', [UserController::class, 'register'])->name('register.store');
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.store');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/mypage', [ArticleController::class, 'mypage'])->name('mypage');

    // Qiitaから記事情報を取得
    Route::get('/qiita/auth', [QiitaController::class, 'redirectToQiita'])->name('qiita.auth');
    Route::get('/qiita/callback', [QiitaController::class, 'handleCallback'])->name('qiita.callback');
    Route::get('/qiita/sync', [QiitaController::class, 'syncQiitaArticles'])->name('qiita.sync');

    // 手動投稿
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');

    // Article
    Route::get('/', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::post('/articles/{id}', [ArticleController::class, 'update'])->name('articles.update');
    Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy');
});





