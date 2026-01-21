<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Yeni Menü Ekle</h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/menus" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<article>
    <form action="/admin/menus/store" method="post">
        <?= csrf_field() ?>

        <div class="grid">
            <div>
                <label for="name">Menü İsmi</label>
                <input type="text" id="name" name="name" required value="<?= old('name') ?>"
                    placeholder="Örn: Ana Menü">

                <label for="location">Konum (Location)</label>
                <input type="text" id="location" name="location" required value="<?= old('location') ?>"
                    placeholder="header_menu">
                <small>Temada menüyü çağırmak için kullanılacak kod.</small>

                <button type="submit" class="primary" style="margin-top: 1rem;">
                    <i class="fa-solid fa-save"></i> Kaydet
                </button>
            </div>

            <div>
                <article class="secondary-box">
                    <header><strong>Bilgi</strong></header>
                    <p>Menüyü oluşturduktan sonra düzenleme ekranından menü öğelerini (linkleri) ekleyebilirsiniz.</p>
                </article>
            </div>
        </div>
    </form>
</article>

<?= $this->endSection() ?>
