<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Düzenle:
            <?= esc($contentType->name) ?>
        </h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/content-types" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<div class="grid">
    <!-- Settings -->
    <article>
        <header><strong>Ayarlar</strong></header>
        <form action="/admin/content-types/update/<?= $contentType->id ?>" method="post">
            <?= csrf_field() ?>

            <label for="name">İsim</label>
            <input type="text" id="name" name="name" required value="<?= old('name', $contentType->name) ?>">

            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" required value="<?= old('slug', $contentType->slug) ?>">

            <fieldset>
                <label>
                    <input type="checkbox" name="has_categories" value="1" <?= old('has_categories', $contentType->has_categories) ? 'checked' : '' ?>>
                    Kategorileri olsun
                </label>
                <label>
                    <input type="checkbox" name="has_seo_fields" value="1" <?= old('has_seo_fields', $contentType->has_seo_fields) ? 'checked' : '' ?>>
                    SEO alanları otomatik eklensin
                </label>
                <label>
                    <input type="checkbox" name="visible" value="1" <?= old('visible', $contentType->visible) ? 'checked' : '' ?>>
                    Admin menüde göster
                </label>
            </fieldset>

            <button type="submit" class="primary" style="width: 100%;">Güncelle</button>
        </form>
    </article>

    <!-- Fields Management -->
    <article style="grid-column: span 2;">
        <header><strong>Alanlar (Custom Fields)</strong></header>

        <div
            style="text-align: center; padding: 2rem; border: 2px dashed var(--pico-muted-border-color); color: var(--pico-muted-color);">
            <i class="fa-solid fa-list fa-2x"></i><br>
            Alan Yönetimi (TextField, Image, Editor vb. ekleme) Yakında...
        </div>

        <!-- Future: List fields from ContentTypeFieldRepository -->
    </article>
</div>

<?= $this->endSection() ?>