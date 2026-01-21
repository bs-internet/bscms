<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Kategori Düzenle</h1>
        <small>
            <?= esc($category->name) ?>
        </small>
    </div>
    <div style="text-align: right;">
        <a href="/App\Core\Modules\Category\Views\<?= $contentType->id ?>" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> Geri Dön
        </a>
    </div>
</div>

<article>
    <form action="/App\Core\Modules\Category\Views\<?= $contentType->id ?>/update/<?= $category->id ?>" method="post">
        <?= csrf_field() ?>

        <div class="grid">
            <div style="grid-column: span 2;">
                <label for="name">Kategori İsmi</label>
                <input type="text" id="name" name="name" required value="<?= old('name', $category->name) ?>">

                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="<?= old('slug', $category->slug) ?>">
            </div>

            <div>
                <label for="parent_id">Üst Kategori</label>
                <select name="parent_id" id="parent_id">
                    <option value="">Yok (Ana Kategori)</option>
                    <?php foreach ($categories as $cat): ?>
                        <?php if ($cat->id !== $category->id): // Prevent picking self ?>
                            <option value="<?= $cat->id ?>" <?= (old('parent_id', $category->parent_id) == $cat->id) ? 'selected' : '' ?>>
                                <?= esc($cat->name) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>

                <label for="sort_order">Sıralama</label>
                <input type="number" id="sort_order" name="sort_order"
                    value="<?= old('sort_order', $category->sort_order) ?>">

                <button type="submit" class="primary" style="width: 100%; margin-top: 1rem;">
                    <i class="fa-solid fa-save"></i> Güncelle
                </button>
            </div>
        </div>
    </form>
</article>

<?= $this->endSection() ?>
