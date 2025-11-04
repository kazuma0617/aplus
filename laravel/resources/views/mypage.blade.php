@extends('layout')

@section('content')
    <h1>マイページ</h1>

    <a href="{{ route('qiita.auth') }}">Qiitaアカウントを連携する</a> |
    <a href="{{ route('qiita.sync') }}">Qiita記事を同期する</a>


    @isset($articles)
        <h2>あなたのQiita記事一覧</h2>
        <ul>
            @foreach ($articles as $article)
            <div class="article-card">
                <h3>{{ $article['title'] }}</h3>
                
                @if($article['image_path'])
                    <img src="{{ asset('storage/' . $article['image_path']) }}" width="200">
                @endif
                <p>
                    <a href="{{ $article['url'] }}" target="_blank">Qiita記事を見る</a><br>
                    @if($article['github_url'])
                        <a href="{{ $article['github_url'] }}" target="_blank">GitHubを見る</a>
                    @endif
                </p>
            </div>
            <a href="{{ route('articles.edit', $article->id) }}">編集</a>
            @endforeach

        </ul>
    @endisset

@endsection