<?php

namespace App\Http\Controllers;

use App\Models\Qiita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class QiitaController extends Controller
{
    // 認証画面にリダイレクト
    public function redirectToQiita()
    {
        $query = http_build_query([
            'client_id' => env('QIITA_CLIENT_ID'),
            'scope' => 'read_qiita',
            'state' => csrf_token(),
        ]);

        return redirect("https://qiita.com/api/v2/oauth/authorize?$query");
    }

    // コールバックでトークンを受け取る
    public function handleCallback(Request $request)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://qiita.com/api/v2/access_tokens', [
            'client_id' => env('QIITA_CLIENT_ID'),
            'client_secret' => env('QIITA_CLIENT_SECRET'),
            'code' => $request->code,
        ]);

        $data = $response->json();
        $token = $data['token'] ?? null;

        $user = Auth::user(); // ← 固定ID

        Qiita::updateOrCreate(
            ['user_id' => $user->id],
            ['qiita_token' => $token]
        );

        return redirect()->route('mypage');
    }

    public function syncQiitaArticles()
    {
        $user = Auth::user();
        $qiita = Qiita::where('user_id', $user->id)->first();

        if (! $qiita || ! $qiita->qiita_token) {
            return redirect()->route('mypage');
        }

        $token = $qiita->qiita_token;

        // QiitaAPIから記事一覧を取得
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->get('https://qiita.com/api/v2/authenticated_user/items');

        $articles = $response->json();

        // ★ DB保存はしない
        // ★ プレビュー画面へ渡す
        return view('qiita-preview', ['qiitaArticles' => $articles]);

    }

    public function import(Request $request)
    {
        $user = Auth::user();

        $article = \App\Models\Article::updateOrCreate(
            [
                'url' => $request->url,
                'user_id' => $user->id,
            ],
            [
                'title' => $request->title,
            ]
        );

        return redirect()->route('mypage');
    }
}
