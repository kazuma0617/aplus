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
