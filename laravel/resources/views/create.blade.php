@extends('layout')

@section('content')
<form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- レイアウト上部 -->
    <div class="layout-top">
        <div class="top-content">
            <a href="{{ route('articles.index') }}" class="back">←</a>
            <button class=submit type="submit" id="post-product">投稿</button>
        </div>
    </div>

    <!-- レイアウトメイン部分 -->
     <div class="layout-main">
        <div class="post-title">
            <input type="text" id="title" name="title" placeholder="オリプロのタイトルを入力" value="{{old('title')}}" required>
        </div>
     </div>

     <div id="image-upload-area" data-sample-image="{{ asset('images/sample.png') }}">
        <input type="file" name="image_path" id="imageInput" accept="image/*" style="display:none;">

        <label for="imageInput" id="imageLabel">
            <img id="preview" src="{{ asset('images/default.jpg') }}" alt="サンプル画像" class="sample-image">
            <p class="select-text">クリックして画像を選択</p>
        </label>
    </div>


     <!-- <div class="post-images">
        <div class="mb-3">
            <label class="form-label">サムネイル画像（任意）</label>
            <input type="file" name="image_path" class="form-control" id="imageInput">
            <div class="image-preview">
            <img id="preview" src="" alt="プレビュー画像" style="display:none; max-width: 300px; margin-top: 10px; border-radius: 10px;">
            </div>
        </div>
    </div> -->

        <div class="post-product-url">
            <label for="product-url">サイトURL</label>
            <input type="url" id="product_url" name="url" placeholder="オリプロのURL" >
        </div>
        <div class="post-github-url">
            <label for="github-url">GithubURL</label>
            <input type="url" id="github_url" name="github_url" placeholder="GithubURL" >
        </div>

        <div class="tag-select-area">
            <p class="tag-select-title">タグ設定</p>
            <div class="tag-select-group">
                <select name="tags[]" required>
                    <option value="" selected disabled>選択してください</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>

                <select name="tags[]" required>
                    <option value="" selected disabled>選択してください</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</form>

<script src="{{ asset('/js/preview.js') }}"></script>

@endsection

<!-- <div class="container" style="max-width:600px; margin-top:40px;">
    <h2>新規記事投稿</h2>

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

        <div class="tag-select-area">
            <p class="tag-select-title">タグ設定</p>
            <div class="tag-select-group">
                <select name="tags[]" required>
                    <option value="" selected disabled>選択してください</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>

                <select name="tags[]" required>
                    <option value="" selected disabled>選択してください</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">投稿する</button>
    </form>
</div> -->
