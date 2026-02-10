<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1><?= lang('Media.title') ?></h1>
        <small><?= lang('Admin.total_size') ?>:
            <?= $totalSizeFormatted ?>
        </small>
    </div>
    <div style="display:flex; align-items:center; gap:0.5rem; justify-content:flex-end;">
        <!-- Bulk Delete Button (hidden until selection) -->
        <button id="bulkDeleteBtn" class="contrast outline" style="display:none;" onclick="bulkDeleteMedia()">
            <i class="fa-solid fa-trash"></i> <span id="bulkCount">0</span> Dosya Sil
        </button>

        <!-- Upload Area -->
        <label class="primary" role="button" style="margin-bottom:0; cursor:pointer;">
            <i class="fa-solid fa-cloud-arrow-up"></i> Dosya Yükle
            <input type="file" id="mediaUpload" multiple style="display:none;">
        </label>
    </div>
</div>

<hr>

<div id="mediaGrid" class="grid" style="grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
    <?php if (empty($media)): ?>
        <p><?= lang('Media.no_files') ?></p>
    <?php else: ?>
        <?php foreach ($media as $item): ?>
            <article class="media-card" data-id="<?= $item->id ?>"
                style="padding: 0; overflow: hidden; position: relative; cursor: grab;">

                <!-- Checkbox for bulk select -->
                <label class="media-checkbox" style="position:absolute; top:6px; left:6px; z-index:2;">
                    <input type="checkbox" class="media-select" value="<?= $item->id ?>" onchange="updateBulkUI()">
                </label>

                <?php if (strpos($item->mimetype, 'image/') === 0): ?>
                    <img src="<?= base_url($item->filepath) ?>" alt="<?= esc($item->filename) ?>"
                        style="width: 100%; height: 150px; object-fit: cover;">
                <?php else: ?>
                    <div
                        style="height: 150px; display: flex; align-items: center; justify-content: center; background: var(--pico-card-background-color);">
                        <i class="fa-solid fa-file fa-3x"></i>
                    </div>
                <?php endif; ?>

                <footer
                    style="padding: 0.5rem; font-size: 0.8em; display: flex; justify-content: space-between; align-items: center;">
                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100px;">
                        <?= esc($item->filename) ?>
                    </span>
                    <a href="/admin/media/delete/<?= $item->id ?>"
                        onclick="return confirm('<?= lang('Media.delete_confirm') ?>')" class="contrast"
                        style="color: var(--pico-del-color);">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </footer>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    // ── Bulk Select ─────────────────────────────────────
    function updateBulkUI() {
        const checked = document.querySelectorAll('.media-select:checked');
        const btn = document.getElementById('bulkDeleteBtn');
        const count = document.getElementById('bulkCount');

        if (checked.length > 0) {
            btn.style.display = 'inline-flex';
            count.textContent = checked.length;
        } else {
            btn.style.display = 'none';
        }
    }

    function bulkDeleteMedia() {
        const checked = document.querySelectorAll('.media-select:checked');
        const ids = Array.from(checked).map(el => el.value);

        if (!ids.length) return;
        if (!confirm(ids.length + ' dosya silinecek. Emin misiniz?')) return;

        const csrfName = document.querySelector('meta[name="csrf-name"]')?.content || 'csrf_test_name';
        const csrfValue = document.querySelector('meta[name="csrf-value"]')?.content || '';

        const fd = new FormData();
        ids.forEach(id => fd.append('ids[]', id));
        fd.append(csrfName, csrfValue);

        fetch('/admin/media/bulk-delete', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    toast(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    toast(data.message, 'error');
                }
            })
            .catch(() => toast('Bir hata oluştu.', 'error'));
    }

    // ── Drag-and-drop Reorder (SortableJS already loaded globally) ───
    if (typeof Sortable !== 'undefined') {
        const grid = document.getElementById('mediaGrid');
        if (grid) {
            Sortable.create(grid, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                handle: '.media-card',
            });
        }
    }

    // ── Upload ──────────────────────────────────────────
    document.getElementById('mediaUpload').addEventListener('change', function (e) {
        const csrfName = document.querySelector('meta[name="csrf-name"]')?.content || 'csrf_test_name';
        const csrfValue = document.querySelector('meta[name="csrf-value"]')?.content || '';

        for (let file of this.files) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append(csrfName, csrfValue);

            fetch('/admin/media/upload', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toast('Yüklendi: ' + data.media.filename, 'success');
                    } else {
                        toast(data.message, 'error');
                    }
                })
                .catch(error => toast('Yükleme hatası', 'error'));
        }

        setTimeout(() => window.location.reload(), 2000);
    });
</script>

<style>
    .media-card {
        transition: transform .15s, box-shadow .15s;
    }

    .media-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
    }

    .sortable-ghost {
        opacity: .4;
    }

    .media-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin: 0;
    }
</style>

<?= $this->endSection() ?>