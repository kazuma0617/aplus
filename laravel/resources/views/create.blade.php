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
<script>
const tagInput = document.getElementById("tag-input");
const tagContainer = document.getElementById("tag-container");
const tagSuggest = document.getElementById("tag-suggest");
const hiddenTags = document.getElementById("tags-hidden");

let tags = [];
let suggestions = []; // DB から API で取得する予定

// サジェスト取得 API 呼び出し
tagInput.addEventListener("input", async () => {
    const keyword = tagInput.value.trim();
    if (!keyword) {
        tagSuggest.style.display = "none";
        return;
    }

    const response = await fetch(`/tags/suggest?keyword=${keyword}`);
    suggestions = await response.json();

    tagSuggest.innerHTML = "";
    suggestions.forEach(tag => {
        const li = document.createElement("li");
        li.textContent = tag.name;
        li.onclick = () => addTag(tag.name);
        tagSuggest.appendChild(li);
    });

    tagSuggest.style.display = "block";
});

// Enter でタグ追加
tagInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        e.preventDefault();
        addTag(tagInput.value.trim());
    }
});

function addTag(tagName) {
    if (!tagName || tags.includes(tagName) || tags.length >= 5) return;

    tags.push(tagName);
    hiddenTags.value = JSON.stringify(tags); // hidden に格納

    // UI にバッジ追加
    const badge = document.createElement("span");
    badge.className = "tag-badge";
    badge.innerHTML = `${tagName} <span class="tag-remove">×</span>`;

    badge.querySelector(".tag-remove").onclick = () => {
        removeTag(tagName, badge);
    };

    tagContainer.appendChild(badge);

    tagInput.value = "";
    tagSuggest.style.display = "none";
}

function removeTag(tagName, badgeEl) {
    tags = tags.filter(t => t !== tagName);
    hiddenTags.value = JSON.stringify(tags);
    badgeEl.remove();
}

</script>

@endsection
