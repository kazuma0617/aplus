@extends('layout')

@section('content')
    <h1>{{ $article->title }} の編集</h1>

    <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label>GitHub URL：</label>
            <input type="text" name="github_url" value="{{ $article->github_url }}">
        </div>

        <div>
            <label>画像：</label>
            @if($article->image_path)
                <img src="{{ asset('storage/' . $article->image_path) }}" width="200"><br>
            @endif
            <input type="file" name="image">
        </div>

        <button type="submit">更新</button>
    </form>
@endsection

