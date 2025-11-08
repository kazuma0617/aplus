@extends('layout')

@section('content')
<div class="container" style="max-width: 800px; margin-top: 40px;">
    <h2>記事一覧</h2>

    @if($articles->isEmpty())
        <p>まだ記事がありません。</p>
    @else
        @foreach($articles as $article)
            <div class="article-card" style="border-bottom:1px solid #ddd; padding:12px 0;">
                <a href="{{ route('articles.show', $article->id) }}"><h3>{{ $article->title }}</h3></a>
                <p>投稿者：{{ optional($article->user)->name ?? '不明なユーザー' }}</p>
                <p>
                    タグ：
                    @foreach($article->tags as $tag)
                        <span class="badge bg-secondary">#{{ $tag->name }}</span>
                    @endforeach
                </p>
            </div>
        @endforeach
    @endif
</div>
@endsection
