<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Bileşen Düzenle</h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/components" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<div class="grid">
    <article>
        <header><strong>Ayarlar</strong></header>
        <!-- Form action mocked based on pattern -->
        <form action="#" method="post">
            <?= csrf_field() ?>
            <label for="name">İsim</label>
            <input type="text" id="name" name="name" value="">
            <button type="submit" class="primary">Güncelle</button>
        </form>
    </article>

    <article>
        <header><strong>Bileşen Alanları</strong></header>
        <div
            style="text-align: center; padding: 2rem; border: 2px dashed var(--pico-muted-border-color); color: var(--pico-muted-color);">
            <i class="fa-solid fa-cubes fa-2x"></i><br>
            Bileşen Yapılandırması Yakında...
        </div>
    </article>
</div>

<?= $this->endSection() ?>