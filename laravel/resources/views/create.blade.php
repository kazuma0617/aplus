@extends('layout-no-bootstrap')

@push('style')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endpush

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

        <div class="post-product-url">
            <label for="product-url">サイトURL</label>
            <input type="url" id="product_url" name="url" placeholder="オリプロのURL" >
        </div>
        <div class="post-github-url">
            <label for="github-url">GithubURL</label>
            <input type="url" id="github_url" name="github_url" placeholder="GithubURL" >
        </div>

       <div class="tag-input-area">
    <label class="tag-label">タグ（最大5つ）</label>

    <div id="tag-container" class="tag-container"></div>

    <input type="text" id="tag-input" class="tag-input" placeholder="タグを入力して Enter で追加">

    <!-- サジェスト表示 -->
    <ul id="tag-suggest" class="tag-suggest"></ul>

    <!-- hidden で送信用 -->
    <input type="hidden" name="tags" id="tags-hidden">
</div>


</form>

<script src="{{ asset('/js/preview.js') }}"></script>
<script src="{{ asset('/js/tag-suggest.js') }}"></script>

@endsection
