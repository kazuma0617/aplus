@extends('layout')

@section('content')
    <div class="container py-5" style="max-width: 700px;">
        <h2 class="mb-4 text-center">記事の新規登録</h2>

        <form action="{{ route('store') }}" method="POST" enctype="multipart/form-data" class="container mt-4">
            @csrf

            <div class="mb-3">
                <label for="image" class="form-label">画像</label>
                <input type="file" class="form-control" name="image" id="image" onchange="previewImage(event)">
                <img id="preview" src="#" alt="プレビュー" style="display:none; width:150px; margin-top:10px;">
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">タイトル</label>
                <input type="text" class="form-control" name="title" id="title" required>
            </div>

            <div class="mb-3">
                <label for="article_url" class="form-label">記事URL</label>
                <input type="url" class="form-control" name="article_url" id="article_url">
            </div>

            <div class="mb-3">
                <label for="github_url" class="form-label">GitHub URL</label>
                <input type="url" class="form-control" name="github_url" id="github_url">
            </div>

            <div class="mb-3">
                <label for="tags" class="form-label">タグ</label>
                <select class="form-select" name="tags[]" id="tags" multiple>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">投稿</button>
        </form>

        <script>
            function previewImage(event) {
                const input = event.target;
                const preview = document.getElementById('preview');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.src = "";
                    preview.style.display = 'none';
                }
            }
        </script>
    </div>
@endsection
