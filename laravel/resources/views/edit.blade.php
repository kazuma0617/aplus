@extends('layout-no-bootstrap')

@section('content')
<form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- レイアウト上部 -->
    <div class="layout-top">
        <div class="top-content">
            <a href="{{ route('mypage') }}" class="back">←</a>
            <button class=submit type="submit" id="post-product">更新</button>
        </div>
    </div>

    <!-- レイアウトメイン部分 -->
     <div class="layout-main">
        <div class="post-title">
            <input type="text" id="title" name="title" placeholder="オリプロのタイトルを入力" value="{{ old('title', $article->title) }}"
 required>
        </div>
     </div>

     <div id="image-upload-area" data-sample-image="{{ asset('images/sample.png') }}">
        <input type="file" name="image_path" id="imageInput" accept="image/*" style="display:none;">

        <label for="imageInput" id="imageLabel">
            <img id="preview"
     src="{{ $article->image_path ? asset('storage/'.$article->image_path) : asset('images/default.jpg') }}"
     class="sample-image">

            <p class="select-text">クリックして画像を選択</p>
        </label>
    </div>
        <div class="post-product-url">
            <label for="product-url">サイトURL</label>
            <input type="url" id="product_url" name="url" placeholder="オリプロのURL" value="{{ old('url', $article->url) }}"
 >
        </div>
        <div class="post-github-url">
            <label for="github-url">GithubURL</label>
            <input type="url" id="github_url" name="github_url" placeholder="GithubURL" value="{{ old('github_url', $article->github_url) }}"
 >
        </div>
    <div class="tag-select-area">
    <p class="tag-select-title">タグ設定</p>

    @php
        // 記事が持っているタグを(0番目,1番目)に分割
        $selectedTags = $article->tags->pluck('id')->values()->toArray();
        $selected1 = $selectedTags[0] ?? null;
        $selected2 = $selectedTags[1] ?? null;
    @endphp

    <div class="tag-select-group">

        {{-- 1つ目のタグ --}}
        <select name="tags[]" required>
            <option value="" disabled>選択してください</option>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}"
                    @selected($selected1 == $tag->id)>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>

        {{-- 2つ目のタグ --}}
        <select name="tags[]" required>
            <option value="" disabled>選択してください</option>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}"
                    @selected($selected2 == $tag->id)>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>

    </div>
</div>

</div>

</form>

<script src="{{ asset('/js/preview.js') }}"></script>

@endsection