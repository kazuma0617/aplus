<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\QiitaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DiscordRegisterController;
use App\Models\Article;
use Illuminate\Support\Facades\Route;

// 認証
Route::get('/register', [UserController::class, 'showRegisterForm1'])->name('register1');
Route::post('/register', [UserController::class, 'sendDiscordRegisterCode'])->name('register1');
Route::get('/register2', [UserController::class, 'showRegisterForm2'])->name('register2');
Route::post('/register2', [UserController::class, 'newRegister'])->name('register.submit');
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Qiita OAuth コールバック（←必ず auth 外）
Route::get('/qiita/callback', [QiitaController::class, 'handleCallback'])->name('qiita.callback');

Route::middleware('auth')->group(function () {
    Route::get('/mypage', [ArticleController::class, 'showMyPage'])->name('mypage');

    // Qiitaから記事情報を取得
    Route::get('/qiita/auth', [QiitaController::class, 'redirectToQiita'])->name('qiita.auth');
    // Route::get('/qiita/callback', [QiitaController::class, 'handleCallback'])->name('qiita.callback');
    Route::get('/qiita/sync', [QiitaController::class, 'syncQiitaArticles'])->name('qiita.sync');
    // プレビュー画面
    Route::get('/qiita/preview', [QiitaController::class, 'syncQiitaArticles'])->name('qiita.preview');

    // 保存処理
    Route::post('/qiita/import', [QiitaController::class, 'import'])->name('qiita.import');

    // 手動投稿
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');

    // Article
    Route::get('/', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::post('/articles/{id}', [ArticleController::class, 'update'])->name('articles.update');
    // Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy');
});

Route::get('/discord/register', [DiscordRegisterController::class, 'showRegisterForm'])->name('discord.register.form');
Route::post('/discord/register/send', [DiscordRegisterController::class, 'sendDiscordRegisterCode'])->name('discord.register.send');
Route::get('/discord/register/confirm', [DiscordRegisterController::class, 'showConfirmForm'])->name('discord.register.confirm.form');
Route::post('/discord/register/confirm', [DiscordRegisterController::class, 'confirmRegisterCode'])->name('discord.register.confirm');
