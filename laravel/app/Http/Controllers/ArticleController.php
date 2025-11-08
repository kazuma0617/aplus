<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
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
        $articles = Article::all();
        return view('mypage', compact('articles'));
    }
}
