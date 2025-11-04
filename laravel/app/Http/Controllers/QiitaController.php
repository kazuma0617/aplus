<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

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

        $user = \App\Models\User::find(1);
        $user->qiita_token = $data['token'] ?? null;
        $user->save();

        return redirect()->route('mypage');
    }

    public function syncQiitaArticles()
    {
        $user = \App\Models\User::find(1);
        $token = $user->qiita_token;

        // QiitaAPIから記事一覧を取得
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->get('https://qiita.com/api/v2/authenticated_user/items');

        $articles = $response->json();

        // DBに保存（すでにあるものは更新、なければ新規作成）
        foreach ($articles as $item) {
            \App\Models\Article::updateOrCreate(
                [
                    'url' => $item['url'],
                    'user_id' => $user->id,
                ],
                [
                    'title' => $item['title'],
                    'created_at' => $item['created_at'],
                ]
            );
        }

        return redirect()->route('mypage');
    }

}
