<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
use App\Models\Qiita;

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

    // Qiita記事の同期
    public function syncQiitaArticles()
    {
        $user = Auth::user();
        $qiita = Qiita::where('user_id', $user->id)->first();

        if (!$qiita || !$qiita->qiita_token) {
            return redirect()->route('mypage')->with('error', 'Qiita連携がまだ完了していません。');
        }

        $token = $qiita->qiita_token;

        // QiitaAPIから記事一覧を取得
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->get('https://qiita.com/api/v2/authenticated_user/items');

        $articles = $response->json();

        // DBに保存（すでにあるものは更新、なければ新規作成）
        foreach ($articles as $item) {
            $article = \App\Models\Article::updateOrCreate(
                [
                    'url' => $item['url'],
                    'user_id' => $user->id,
                ],
                [
                    'title' => $item['title'],
                    'created_at' => $item['created_at'],
                ]
            );

            // タグの同期
            $tagIds = [];
            if (isset($item['tags'])) {
                foreach ($item['tags'] as $tagData) {
                    $tag = \App\Models\Tag::firstOrCreate(['name' => $tagData['name']]);
                    $tagIds[] = $tag->id;
                }
                $article->tags()->sync($tagIds);
            }
        }

        return redirect()->route('mypage')->with('success', 'Qiita記事を同期しました。');
    }
}
