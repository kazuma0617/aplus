@extends('layout')

@push('style')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endpush

@section('content')

    @if(Auth::check())
        <div style="text-align: right; margin-bottom: 10px;">
            <span>ようこそ、{{ Auth::user()->name }} さん</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">ログアウト</button>
            </form>
        </div>
    @endif

    <a href="{{ route('articles.index') }}" class="btn-back">← 戻る</a>
    <a href="{{ route('qiita.auth') }}" class="btn-qiita">Qiitaアカウントを連携する</a> 
    <a href="{{ route('qiita.sync') }}" class="btn-qiita">Qiita記事を同期する</a>

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
                <h3>{{ $article->title }}</h3>
                <p>
                    @foreach($article->tags as $tag)
                        <span class="tag">#{{ $tag->name }}</span>
                    @endforeach
                </p>
                <div class="article-actions">
                    <a href="{{ route('articles.edit', $article->id) }}" class="edit-btn"><i class="bi bi-pen"></i></a>
                        <!-- フォームでDeleteメソッドとして実行 -->
                        <form action="{{ route('articles.destroy', $article->id) }}" 
                            method="POST" 
                            onsubmit="return confirm('本当に削除しますか？');" 
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    

@endsection