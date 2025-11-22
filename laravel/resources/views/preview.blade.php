@extends('layout')

@section('content')
<h1>Qiita記事プレビュー</h1>

@foreach ($qiitaArticles as $article)
<div class="article-card">
    <div class="article-right">
        <h3>{{ $article['title'] }}</h3>
        @foreach ($article['tags'] as $tag)
            <span class="tag">#{{ $tag['name'] }}</span>
        @endforeach

        <form method="POST" action="{{ route('qiita.import') }}">
            @csrf
            <input type="hidden" name="title" value="{{ $article['title'] }}">
            <input type="hidden" name="url" value="{{ $article['url'] }}">
            <button type="submit">追加する</button>
        </form>
    </div>
</div>
@endforeach

@endsection
