<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Yeni Kategori Ekle</h1>
        <small>
            <?= esc($contentType->title) ?>
        </small>
    </div>
    <div style="text-align: right;">
        <a href="/admin/categories/<?= $contentType->id ?>" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<article>
    <form action="/admin/categories/<?= $contentType->id ?>/store" method="post">
        <?= csrf_field() ?>

        <div class="grid">
            <div style="grid-column: span 2;">
                <label for="name">Kategori İsmi</label>
                <input type="text" id="name" name="name" required value="<?= old('name') ?>"
                    placeholder="Örn: Teknoloji">

                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="<?= old('slug') ?>" placeholder="otomatik-olusturulur">
            </div>

            <div>
                <label for="parent_id">Üst Kategori</label>
                <select name="parent_id" id="parent_id">
                    <option value="">Yok (Ana Kategori)</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat->id ?>" <?= old('parent_id') == $cat->id ? 'selected' : '' ?>>
                            <?= esc($cat->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="sort_order">Sıralama</label>
                <input type="number" id="sort_order" name="sort_order" value="<?= old('sort_order', 0) ?>">

                <button type="submit" class="primary" style="width: 100%; margin-top: 1rem;">
                    <i class="fa-solid fa-save"></i> Kaydet
                </button>
            </div>
        </div>
    </form>
</article>

<?= $this->endSection() ?>