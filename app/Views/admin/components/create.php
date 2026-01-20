<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Yeni Bileşen</h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/components" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<article>
    <form action="/admin/components/store" method="post">
        <?= csrf_field() ?>

        <div class="grid">
            <div style="grid-column: span 2;">
                <label for="name">Bileşen İsmi</label>
                <input type="text" id="name" name="name" required value="<?= old('name') ?>"
                    placeholder="Örn: Manset Slider">

                <label for="component_key">Kod (Key)</label>
                <input type="text" id="component_key" name="component_key" required value="<?= old('component_key') ?>"
                    placeholder="hero_slider">

                <label>
                    <input type="checkbox" name="is_global" value="1" <?= old('is_global') ? 'checked' : '' ?>>
                    Global Bileşen (Tüm sayfalarda aynı veri kullanılır)
                </label>

                <button type="submit" class="primary" style="margin-top: 1rem;">
                    <i class="fa-solid fa-save"></i> Kaydet
                </button>
            </div>
        </div>
    </form>
</article>

<?= $this->endSection() ?>