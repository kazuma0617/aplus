@extends('layout')

@section('content')
<div class="article-detail">
    <h2>{{ $article->title }}</h2>

    @if($article->image_path)
        <img src="{{ asset('storage/' . $article->image_path) }}" width="300">
    @endif

    {{-- タグ一覧 --}}
    <div class="tags">
        @foreach ($article->tags as $tag)
            <span class="tag">#{{ $tag->name }}</span>
        @endforeach
    </div>

    {{-- リンク --}}
    <div class="links">
        <a href="{{ $article->url }}" target="_blank" class="btn-qiita">Qiita記事を見る</a>
        @if($article->github_url)
            <a href="{{ $article->github_url }}" target="_blank" class="btn-github">GitHubを見る</a>
        @endif
    </div>

    {{-- 戻るボタン --}}
    <a href="{{ route('articles.index') }}" class="btn-back">← 戻る</a>
</div>
@endsection
