<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QiitaController;
use App\Http\Controllers\ArticleController;
use App\Models\Article;

Route::get('/', function () {
    return "home";
});

Route::get('/mypage', function () {
    $articles = Article::all();
    return view('mypage', compact('articles'));
})->name('mypage');


// Qiitaから記事情報を取得
Route::get('/qiita/auth', [QiitaController::class, 'redirectToQiita'])->name('qiita.auth');
Route::get('/qiita/callback', [QiitaController::class, 'handleCallback'])->name('qiita.callback');
Route::get('/qiita/sync', [QiitaController::class, 'syncQiitaArticles'])->name('qiita.sync');

// Article
Route::get('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
Route::post('/articles/{id}', [ArticleController::class, 'update'])->name('articles.update');




