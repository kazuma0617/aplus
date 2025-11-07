@extends('layout')

@section('content')

    <a href="{{ route('qiita.auth') }}" class="btn-qiita">Qiitaアカウントを連携する</a> 
    <a href="{{ route('qiita.sync') }}" class="btn-qiita">Qiita記事を同期する</a>


    @isset($articles)
        <h2>あなたのQiita記事一覧</h2>
        <ul>
            @foreach ($articles as $article)
            <a href="{{ route('articles.show', $article->id) }}">
                <div class="article-card">
                    @if($article['image_path'])
                        <img src="{{ asset('storage/' . $article['image_path']) }}" alt="記事画像" class="article-image">
                    @endif
                    <div class="article-info">
                        <h3>{{ $article['title'] }}</h3>
                        @foreach($article->tags as $tag)
                        <span>{{ $tag->name }}</span>
                        @endforeach
                        <a href="{{ route('articles.edit', $article->id) }}" class="edit-btn"><i class="bi bi-pen"></i></a>
                        <a href="" class="delete-btn"><i class="bi bi-trash"></i></a>
                    </div>
                    
                </div>
            </a>
            
            @endforeach

        </ul>
    @endisset

@endsection