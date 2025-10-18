@extends('layout')

@section('content')
    <h2>記事一覧</h2>
    <a href="/create">新規投稿</a>
    <div class="articles">
        @foreach ($articles as $article)
        <div class="card mb-3 p-3">
            <h5>
                <a href="{{ url('/articles/' . $article->id) }}">
                    {{ $article->title }}
                </a>
            </h5>
            @if ($article->image_path)
                <img src="{{ asset('storage/' . $article->image_path) }}" 
                    alt="記事画像" style="width:150px; height:100px; object-fit:cover;">
            @endif
            <div class="mt-2">
                @foreach ($article->tags as $tag)
                    <span class="badge bg-secondary">{{ $tag->name }}</span>
                @endforeach
            </div>
            <a href="{{ route('edit', $article->id) }}" class="btn btn-sm btn-warning">編集</a>
            <form action="{{ route('destroy', $article->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
            </form>
        </div>
        @endforeach
    </div>
@endsection
