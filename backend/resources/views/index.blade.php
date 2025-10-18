@extends('layout')

@section('content')
<h2>記事一覧</h2>
<div class="row g-3">
    @foreach ($articles as $article)
    <div class="col-6">
        <div class="card h-100 p-3 d-flex flex-row align-items-start">
            <!-- 左側：画像 -->
            @if ($article->image_path)
            <div style="width:150px; flex-shrink:0;" class="me-3">
                <img src="{{ asset('storage/' . $article->image_path) }}" 
                     alt="記事画像" style="width:100%; height:100px; object-fit:cover;">
            </div>
            @endif

            <!-- 右側：タイトル、タグ、ボタン -->
            <div class="flex-grow-1">
                <h5>
                    <a href="{{ url('/articles/' . $article->id) }}">
                        {{ $article->title }}
                    </a>
                </h5>
                <div class="mb-2">
                    @foreach ($article->tags as $tag)
                        <span class="badge bg-secondary">{{ $tag->name }}</span>
                    @endforeach
                </div>
                <a href="{{ route('edit', $article->id) }}" class="btn btn-sm btn-warning me-2">編集</a>
                <form action="{{ route('destroy', $article->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
