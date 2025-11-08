@extends('layout')

@section('content')
<div class="container" style="max-width:600px; margin-top:40px;">
    <h2>新規記事投稿</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">タイトル</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">記事URL</label>
            <input type="url" name="url" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">GitHub URL（任意）</label>
            <input type="url" name="github_url" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">サムネイル画像（任意）</label>
            <input type="file" name="image_path" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">タグを選択（複数可）</label>
            <select name="tags[]" class="form-select" multiple size="6">
                @foreach($tags as $tag)
                <option value="{{ $tag->id }}"
                    @if(collect(old('tags', []))->contains($tag->id)) selected @endif>
                    {{ $tag->name }}
                </option>
                @endforeach
            </select>
            <small class="text-muted">Ctrl/⌘ を押しながら複数選択できます</small>
        </div>

        <button type="submit" class="btn btn-primary w-100">投稿する</button>
    </form>
</div>
@endsection
