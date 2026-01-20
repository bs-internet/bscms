<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Sitemap Yönetimi</h1>
        <small>SEO için site haritasını yönetin.</small>
    </div>
</div>

<article>
    <div class="grid">
        <div>
            <h5>Durum</h5>
            <?php if ($sitemapExists): ?>
                <p style="color: var(--pico-ins-color);">
                    <i class="fa-solid fa-check-circle"></i> Sitemap mevcut.
                </p>
                <small>Son güncelleme:
                    <?= $lastGenerated ?>
                </small>
                <div style="margin-top: 1rem;">
                    <a href="/sitemap.xml" target="_blank" role="button" class="secondary outline btn-sm">Görüntüle
                        (XML)</a>
                    <a href="/admin/sitemap/view" role="button" class="secondary outline btn-sm">İncele</a>
                </div>
            <?php else: ?>
                <p style="color: var(--pico-del-color);">
                    <i class="fa-solid fa-circle-xmark"></i> Sitemap henüz oluşturulmamış.
                </p>
            <?php endif; ?>
        </div>

        <div style="border-left: 1px solid var(--pico-muted-border-color); padding-left: 2rem;">
            <h5>İşlemler</h5>
            <form action="/admin/sitemap/generate" method="post" style="display: inline-block;">
                <?= csrf_field() ?>
                <button type="submit" class="primary">
                    <i class="fa-solid fa-rotate"></i> Şimdi Oluştur
                </button>
            </form>

            <?php if ($sitemapExists): ?>
                <a href="/admin/sitemap/delete" role="button" class="contrast outline" style="margin-left: 1rem;"
                    onclick="return confirm('Silmek istediğinize emin misiniz?');">
                    <i class="fa-solid fa-trash"></i> Sil
                </a>
            <?php endif; ?>
        </div>
    </div>
</article>

<article>
    <header><strong>Bilgi</strong></header>
    <p>Sitemap, yayınlanmış içeriklerinizi ve içerik türlerinizi tarayarak otomatik oluşturulur. Google Search Console'a
        göndermek için bu dosyayı güncel tutmanız önerilir.</p>
</article>

<?= $this->endSection() ?>