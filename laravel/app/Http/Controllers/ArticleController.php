<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('id', 'desc')->paginate(10);

        return view('index', compact('articles'));
    }

    public function show($id)
    {
        $article = Article::with('tags')->findOrFail($id);

        return view('show', compact('article'));
    }

    public function edit($id)
    {
        $article = Article::with('tags')->findOrFail($id);
        $tags = Tag::orderBy('name')->get(['id', 'name']);

        return view('edit', compact('article', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        // バリデーション（store と合わせる）
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'image_path' => 'nullable|image|max:2048',
            'tags' => 'array',
        ]);

        // フィールドの更新
        $article->title = $request->title;
        $article->url = $request->url;
        $article->github_url = $request->github_url;

        // 画像の再アップロード
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('articles', 'public');
            $article->image_path = $imagePath;
        }

        $article->save();

        // タグの更新（sync）
        $article->tags()->sync($request->input('tags', []));

        return redirect()->route('mypage');
    }

    public function destroy($id)
    {
        $article = \App\Models\Article::findOrFail($id);
        $article->delete();

        return redirect()->route('mypage');
    }

    public function showMyPage()
    {
        $user_id = Auth::id();
        $articles = Article::where('user_id', $user_id)->get();

        return view('mypage', compact('articles'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'image_path' => 'nullable|image|max:2048',
            'tags' => 'nullable|string',   // ← JSON 文字列
        ]);

        // 画像アップロード
        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('articles', 'public');
        }

        // ① 記事作成
        $article = Article::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'url' => $request->url,
            'github_url' => $request->github_url,
            'image_path' => $imagePath,
        ]);

        // ② hidden の JSON からタグ名リスト取得
        $tagNames = json_decode($request->tags, true) ?? [];

        $tagIds = [];

        foreach ($tagNames as $name) {
            // 既存のタグを探す。なければ作成
            $tag = Tag::firstOrCreate(['name' => $name]);
            $tagIds[] = $tag->id;
        }

        // ③ タグを紐づけ
        $article->tags()->sync($tagIds);

        return redirect()->route('mypage');
    }
}
