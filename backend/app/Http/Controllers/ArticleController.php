<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('tags')->get();
        return view('index', compact('articles'));
    }

    public function show($id)
    {
        $article = \App\Models\Article::with('tags')->findOrFail($id);
        return view('show', compact('article'));
    }

    public function create()
    {
        $tags = \App\Models\Tag::all();
        return view('create', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'article_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $article = Article::create([
            'title' => $validated['title'],
            'article_url' => $validated['article_url'] ?? null,
            'github_url' => $validated['github_url'] ?? null,
            'image_path' => $imagePath,
        ]);

        // タグを紐付け
        if (!empty($validated['tags'])) {
            $article->tags()->attach($validated['tags']);
        }

        return redirect()->route('index')->with('success', '記事を投稿しました！');
    }

    public function edit($id)
    {
        $article = \App\Models\Article::with('tags')->findOrFail($id);
        $tags = \App\Models\Tag::all();
        return view('edit', compact('article', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $article = \App\Models\Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'article_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|max:2048',
        ]);

        // 画像の更新
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $article->image_path = $imagePath;
        }

        $article->title = $validated['title'];
        $article->article_url = $validated['article_url'] ?? null;
        $article->github_url = $validated['github_url'] ?? null;
        $article->save();

        // タグの更新
        if (!empty($validated['tags'])) {
            $article->tags()->sync($validated['tags']); // 既存のタグを置き換え
        } else {
            $article->tags()->detach();
        }

        return redirect()->route('index')->with('success', '記事を更新しました！');
    }


}
