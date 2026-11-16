document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[type="file"][data-image-preview]').forEach(function (input) {
        var image = document.getElementById(input.getAttribute('data-image-preview'));
        var empty = document.getElementById(input.getAttribute('data-empty-preview'));
        var temporaryUrl;
        if (!image) return;
        input.addEventListener('change', function () {
            if (temporaryUrl) URL.revokeObjectURL(temporaryUrl);
            var file = input.files && input.files[0];
            if (!file) return;
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                alert('Pilih gambar JPG, PNG, atau WebP.');
                input.value = '';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran gambar maksimal 2 MB.');
                input.value = '';
                return;
            }
            temporaryUrl = URL.createObjectURL(file);
            image.src = temporaryUrl;
            image.hidden = false;
            if (empty) empty.hidden = true;
        });
    });
});
