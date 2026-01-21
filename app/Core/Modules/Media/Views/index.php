<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Medya Kütüphanesi</h1>
        <small>Toplam Boyut:
            <?= $totalSizeFormatted ?>
        </small>
    </div>
    <div>
        <!-- Upload Area (Pico style simple upload or FilePond trigger) -->
        <input type="file" id="mediaUpload" multiple>
    </div>
</div>

<hr>

<div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
    <?php if (empty($media)): ?>
        <p>Henüz dosya yüklenmemiş.</p>
    <?php else: ?>
        <?php foreach ($media as $item): ?>
            <article style="padding: 0; overflow: hidden; position: relative; group">
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
                    <a href="#" hx-get="/App\Core\Modules\Media\Views\index/delete/<?= $item->id ?>"
                        hx-confirm="Bu dosyayı silmek istediğinize emin misiniz?" class="contrast"
                        style="color: var(--pico-del-color);">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </footer>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Simple Script for handling upload (Advanced FilePond integration would go here) -->
<script>
    document.getElementById('mediaUpload').addEventListener('change', function (e) {
        const formData = new FormData();
        for (let file of this.files) {
            formData.append('file', file);
        }

        fetch('/App\Core\Modules\Media\Views\index/upload', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>

<?= $this->endSection() ?>
