@extends('layout')

@section('content')
    <h2>記事一覧</h2>
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
