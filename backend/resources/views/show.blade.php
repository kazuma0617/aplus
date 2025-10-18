@extends('layout')

@section('content')
<div class="container py-5" style="max-width: 700px;">
    <h2 class="mb-4">{{ $article->title }}</h2>

    @if ($article->image_path)
        <img src="{{ asset('storage/' . $article->image_path) }}" 
             alt="記事画像" class="img-fluid rounded mb-3" 
             style="max-height: 300px; object-fit: cover;">
    @endif

    <div class="mb-3">
        <strong>記事URL：</strong>
        @if ($article->article_url)
            <a href="{{ $article->article_url }}" target="_blank">{{ $article->article_url }}</a>
        @else
            <span class="text-muted">未登録</span>
        @endif
    </div>

    <div class="mb-3">
        <strong>GitHub：</strong>
        @if ($article->github_url)
            <a href="{{ $article->github_url }}" target="_blank">{{ $article->github_url }}</a>
        @else
            <span class="text-muted">未登録</span>
        @endif
    </div>

    <div class="mb-3">
        <strong>タグ：</strong>
        @if ($article->tags->isNotEmpty())
            @foreach ($article->tags as $tag)
                <span class="badge bg-secondary">{{ $tag->name }}</span>
            @endforeach
        @else
            <span class="text-muted">なし</span>
        @endif
    </div>

    <div class="mt-3">
        <a href="{{ route('index') }}" 
        class="btn" 
        style="background-color: #B3B3B3; color: #fff;">
            戻る
        </a>
    </div>
</div>
@endsection
