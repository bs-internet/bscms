<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1><?= lang('Content.new_content_type') ?></h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/content-types" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> <?= lang('Admin.back') ?>
        </a>
    </div>
</div>

<article>
    <form action="/admin/content-types/store" method="post">
        <?= csrf_field() ?>

        <div class="grid">
            <div style="grid-column: span 2;">
                <label for="name"><?= lang('Content.content_type_title') ?></label>
                <input type="text" id="name" name="name" required value="<?= old('name') ?>"
                    placeholder="Örn: Blog Yazıları">

                <label for="slug"><?= lang('Content.content_type_slug') ?></label>
                <input type="text" id="slug" name="slug" required value="<?= old('slug') ?>" placeholder="blog">

                <fieldset>
                    <legend><?= lang('Content.features') ?></legend>
                    <label>
                        <input type="checkbox" name="has_categories" value="1" <?= old('has_categories') ? 'checked' : '' ?>>
                        <?= lang('Content.category_support') ?>
                    </label>
                    <label>
                        <input type="checkbox" name="has_seo_fields" value="1" <?= old('has_seo_fields') ? 'checked' : '' ?>>
                        <?= lang('Content.seo_support') ?>
                    </label>
                    <label>
                        <input type="checkbox" name="visible" value="1" <?= old('visible', true) ? 'checked' : '' ?>>
                        <?= lang('Content.show_in_menu') ?>
                    </label>
                </fieldset>

                <button type="submit" class="primary" style="margin-top: 1rem;">
                    <i class="fa-solid fa-save"></i> <?= lang('Admin.save') ?>
                </button>
            </div>

            <div>
                <article class="secondary-box">
                    <header><strong><?= lang('Content.info') ?></strong></header>
                    <p><?= lang('Content.fields_description') ?></p>
                </article>
            </div>
        </div>
    </form>
</article>

<?= $this->endSection() ?>