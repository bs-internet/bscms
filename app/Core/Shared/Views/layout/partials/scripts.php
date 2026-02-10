<!-- HTMX -->
<script src="https://unpkg.com/htmx.org@1.9.10"
    integrity="sha384-D1Kt99CQMDuVetoL1lrYwg5t+9QdHe7NLX/SoJYkXDFfX37iInKRy5xLSi8nO7UC"
    crossorigin="anonymous"></script>

<!-- Toastify -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<!-- Global Admin Scripts -->
<script>
    // Global Toast Function
    window.toast = function (message, type = 'info') {
        let bg = "#333";
        if (type === 'success') bg = "#2ecc71";
        if (type === 'error') bg = "#e74c3c";
        if (type === 'warning') bg = "#f39c12";

        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: bg,
            stopOnFocus: true
        }).showToast();
    }

    // HTMX Events for Toast
    document.body.addEventListener('htmx:afterSwap', function (evt) {
        // If server returns a header with toast message (Custom implementation if needed)
    });

    // Auto-init specific behaviors if needed
</script>

<!-- TinyMCE CDN (Community, no API key needed) -->
<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Media Picker -->
<link rel="stylesheet" href="<?= base_url('assets/admin/css/media-picker.css') ?>?v=<?= time() ?>">
<script src="<?= base_url('assets/admin/js/media-picker.js') ?>?v=<?= time() ?>"></script>

<!-- TinyMCE Init for .rich-editor -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editors = document.querySelectorAll('textarea.rich-editor');
        if (!editors.length) return;

        tinymce.init({
            selector: 'textarea.rich-editor',
            language: 'tr',
            language_url: '/assets/admin/js/tinymce/langs/tr.js',
            height: 400,
            menubar: 'file edit view insert format tools table',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                'preview', 'anchor', 'searchreplace', 'visualblocks', 'code',
                'fullscreen', 'insertdatetime', 'media', 'table', 'wordcount'
            ],
            toolbar: 'undo redo | styles | bold italic underline strikethrough | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image media | ' +
                'table blockquote hr | removeformat code fullscreen',
            // Media Picker integration
            file_picker_types: 'image media file',
            file_picker_callback: function (cb, value, meta) {
                window.openMediaPicker(function (url) {
                    cb(url, { title: url.split('/').pop() });
                });
            },
            // Paste images as upload
            images_upload_url: '/admin/media/upload',
            automatic_uploads: false,
            // Styling
            content_css: false,
            skin: 'oxide',
            branding: false,
            promotion: false,
            relative_urls: false,
            remove_script_host: false
        });
    });
</script>