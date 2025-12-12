@extends('layout')

@push('style')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endpush

@section('content')
<div class="main-contents">
    @foreach($articles as $article)
    <div class="article-card">
        <div class="article-left">
            <img src="{{ $article['image_path'] 
                ? asset('storage/' . $article['image_path']) 
                : asset('images/default.jpg') }}" 
                alt="記事画像" class="article-image">
        </div>
        <div class="article-right">
            <h3><a href="{{ $article->url }}">{{ $article->title }}</a></h3>
            
            
            <p>
                @foreach($article->tags as $tag)
                    <span class="tag">{{ $tag->name }}</span>
                @endforeach
            </p>
            <p class="poster-line">
                @if ($article->github_url)
                <a href="{{ $article->github_url }}" class="github-btn" target="_blank">
                    <i class="bi bi-github"></i>
                </a>
                @endif
                投稿者：{{ optional($article->user)->name }}
            </p>
        </div>
    </div>
    @endforeach

    <div class="pagination-area">
        {{ $articles->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection