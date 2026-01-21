<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Sitemap Önizleme</h1>
        <small>sitemap.xml içeriği</small>
    </div>
    <div style="text-align: right;">
        <a href="/admin/sitemap" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<article>
    <pre><code><?= esc($content) ?></code></pre>
</article>

<?= $this->endSection() ?>
