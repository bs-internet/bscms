<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1><?= lang('Category.edit_category') ?></h1>
        <small>
            <?= esc($category->name) ?>
        </small>
    </div>
    <div style="text-align: right;">
        <a href="/admin/categories/<?= $contentType->id ?>" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> <?= lang('Admin.back') ?>
        </a>
    </div>
</div>

<article>
    <form action="/admin/categories/<?= $contentType->id ?>/update/<?= $category->id ?>" method="post">
        <?= csrf_field() ?>

        <div class="grid">
            <div style="grid-column: span 2;">
                <label for="name"><?= lang('Category.name') ?></label>
                <input type="text" id="name" name="name" required value="<?= old('name', $category->name) ?>">

                <label for="slug"><?= lang('Category.slug') ?></label>
                <input type="text" id="slug" name="slug" value="<?= old('slug', $category->slug) ?>">
            </div>

            <div>
                <label for="parent_id"><?= lang('Category.parent_category') ?></label>
                <select name="parent_id" id="parent_id">
                    <option value=""><?= lang('Category.none') ?> (<?= lang('Category.parent_category') ?>)</option>
                    <?php foreach ($categories as $cat): ?>
                        <?php if ($cat->id !== $category->id): // Prevent picking self ?>
                            <option value="<?= $cat->id ?>" <?= (old('parent_id', $category->parent_id) == $cat->id) ? 'selected' : '' ?>>
                                <?= esc($cat->name) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>

                <label for="sort_order"><?= lang('Category.sort_order') ?></label>
                <input type="number" id="sort_order" name="sort_order"
                    value="<?= old('sort_order', $category->sort_order) ?>">

                <button type="submit" class="primary" style="width: 100%; margin-top: 1rem;">
                    <i class="fa-solid fa-save"></i> <?= lang('Admin.update') ?>
                </button>
            </div>
        </div>
    </form>
</article>

<?= $this->endSection() ?>
