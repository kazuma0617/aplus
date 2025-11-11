@extends('layout')

@section('content')
<div class="main-contents">
    <h1>アプレンティス生オリジナルプロダクト</h1>
    @foreach($articles as $article)
    <div class="article-card">
        <div class="article-left">
            <img src="{{ $article['image_path'] 
                ? asset('storage/' . $article['image_path']) 
                : asset('images/default.jpg') }}" 
                alt="記事画像" class="article-image">
        </div>
        <div class="article-right">
            <a href="{{ route('articles.show', $article->id) }}"><h3>{{ $article->title }}</h3></a>
            <p>
                @foreach($article->tags as $tag)
                    <span class="tag">#{{ $tag->name }}</span>
                @endforeach
            </p>
            <p>投稿者：{{ optional($article->user)->name ?? '不明なユーザー' }}</p>
        </div>
    </div>
    @endforeach
</div>
@endsection