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

}
