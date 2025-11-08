<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{

    public function index()
    {
        $articles = Article::with('user', 'tags')->get();
        return view('index', compact('articles'));
    }

    public function show($id)
    {
        $article = Article::with('tags')->findOrFail($id);
        return view('show', compact('article'));
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('edit', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $article->github_url = $request->github_url;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $article->image_path = $path;
        }

        $article->save();

        return redirect()->route('mypage');
    }

    public function destroy($id)
    {
        $article = \App\Models\Article::findOrFail($id);
        $article->delete();

        return redirect()->route('mypage');
    }

    public function mypage()
    {
        $user_id = Auth::id();
        $articles = Article::where('user_id', $user_id)->get();
        return view('mypage', compact('articles'));
    }

    public function create()
    {
        $tags = Tag::orderBy('name')->get(['id','name']);
        return view('create', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'image_path' => 'nullable|image|max:2048',
        ]);

        // 画像のアップロード処理（任意）
        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('articles', 'public');
        }

        // 保存
        $article = Article::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'url' => $request->url,
            'github_url' => $request->github_url,
            'image_path' => $imagePath,
        ]);

        $article->tags()->sync($request->input('tags', []));
        return redirect()->route('mypage');
    }
}
