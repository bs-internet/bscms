<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1><?= lang('Menu.new_menu') ?></h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/menus" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> <?= lang('Admin.back') ?>
        </a>
    </div>
</div>

<article>
    <form action="/admin/menus/store" method="post">
        <?= csrf_field() ?>

        <div class="grid">
            <div>
                <label for="name"><?= lang('Menu.menu_title') ?></label>
                <input type="text" id="name" name="name" required value="<?= old('name') ?>"
                    placeholder="Örn: Ana Menü">

                <label for="location"><?= lang('Menu.menu_key') ?></label>
                <input type="text" id="location" name="location" required value="<?= old('location') ?>"
                    placeholder="header_menu">
                <small><?= lang('Menu.menu_help') ?></small>

                <button type="submit" class="primary" style="margin-top: 1rem;">
                    <i class="fa-solid fa-save"></i> <?= lang('Admin.save') ?>
                </button>
            </div>

            <div>
                <article class="secondary-box">
                    <header><strong><?= lang('Admin.info') ?></strong></header>
                    <p><?= lang('Menu.menu_links_help') ?></p>
                </article>
            </div>
        </div>
    </form>
</article>

<?= $this->endSection() ?>