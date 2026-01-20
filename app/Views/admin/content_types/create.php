<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Yeni İçerik Türü</h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/content-types" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<article>
    <form action="/admin/content-types/store" method="post">
        <?= csrf_field() ?>

        <div class="grid">
            <div style="grid-column: span 2;">
                <label for="name">İsim</label>
                <input type="text" id="name" name="name" required value="<?= old('name') ?>"
                    placeholder="Örn: Blog Yazıları">

                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" required value="<?= old('slug') ?>" placeholder="blog">

                <fieldset>
                    <legend>Özellikler</legend>
                    <label>
                        <input type="checkbox" name="has_categories" value="1" <?= old('has_categories') ? 'checked' : '' ?>>
                        Kategorileri olsun
                    </label>
                    <label>
                        <input type="checkbox" name="has_seo_fields" value="1" <?= old('has_seo_fields') ? 'checked' : '' ?>>
                        SEO alanları otomatik eklensin
                    </label>
                    <label>
                        <input type="checkbox" name="visible" value="1" <?= old('visible', true) ? 'checked' : '' ?>>
                        Admin menüde göster
                    </label>
                </fieldset>

                <button type="submit" class="primary" style="margin-top: 1rem;">
                    <i class="fa-solid fa-save"></i> Kaydet
                </button>
            </div>

            <div>
                <article class="secondary-box">
                    <header><strong>Bilgi</strong></header>
                    <p>Bu türü oluşturduktan sonra "Düzenle" diyerek özel alanlar (Form Fields) ekleyebilirsiniz.</p>
                </article>
            </div>
        </div>
    </form>
</article>

<?= $this->endSection() ?>