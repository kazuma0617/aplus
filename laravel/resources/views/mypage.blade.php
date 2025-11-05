@extends('layout')

@section('content')

    <a href="{{ route('qiita.auth') }}" class="btn-qiita">Qiitaアカウントを連携する</a> 
    <a href="{{ route('qiita.sync') }}" class="btn-qiita">Qiita記事を同期する</a>


    @isset($articles)
        <h2>あなたのQiita記事一覧</h2>
        <ul>
            @foreach ($articles as $article)
            <div class="article-card">
                
                
                @if($article['image_path'])
                    <img src="{{ asset('storage/' . $article['image_path']) }}" width="200">
                @endif
                <div class="article-info">
                    <h3>{{ $article['title'] }}</h3>
                    <p>
                        <a href="{{ $article['url'] }}" target="_blank">Qiita記事を見る</a><br>
                        @if($article['github_url'])
                            <a href="{{ $article['github_url'] }}" target="_blank">GitHubを見る</a>
                        @endif
                        @foreach ($article->tags as $tag)
                          <span class="tag">{{ $tag->name }}</span>
                        @endforeach
                    </p>
                    <a href="{{ route('articles.edit', $article->id) }}" class="edit-btn"><i class="bi bi-pen"></i></a>
                    <a href="" class="delete-btn"><i class="bi bi-trash"></i></a>
                </div>
            </div>
            
            @endforeach

        </ul>
    @endisset

@endsection