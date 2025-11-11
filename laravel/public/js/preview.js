document.addEventListener('DOMContentLoaded', () => {
    const imageInput = document.getElementById('imageInput');
    const preview = document.getElementById('preview');
    const uploadArea = document.getElementById('image-upload-area');
    const sampleImagePath = uploadArea.dataset.sampleImage;

    if (!imageInput || !preview) return;

    imageInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (!file || !file.type.startsWith('image/')) {
            preview.src = sampleImagePath; // 非画像 or 取消なら戻す
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result; // プレビュー更新
        };
        reader.readAsDataURL(file);
    });
});
