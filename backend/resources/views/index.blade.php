@extends('layout')

@section('content')
    <h2>記事一覧</h2>
    <a href="/create">新規投稿</a>
    <div class="articles">
        @foreach($articles as $article)
            <div class="article-card">
                <h3>{{ $article->title }}</h3>
                <p>
                    @foreach($article->tags as $tag)
                        <span class="tag">{{ $tag->name }}</span>
                    @endforeach
                </p>
            </div>
        @endforeach
    </div>
@endsection
