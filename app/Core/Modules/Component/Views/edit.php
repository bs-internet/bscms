<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1><?= lang('Component.edit_component') ?></h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/components" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> <?= lang('Admin.back') ?>
        </a>
    </div>
</div>

<div class="grid">
    <article>
        <header><strong><?= lang('Admin.settings') ?></strong></header>
        <!-- Form action mocked based on pattern -->
        <form action="#" method="post">
            <?= csrf_field() ?>
            <label for="name"><?= lang('Component.component_title') ?></label>
            <input type="text" id="name" name="name" value="">
            <button type="submit" class="primary"><?= lang('Admin.update') ?></button>
        </form>
    </article>

    <article>
        <header><strong><?= lang('Component.component_fields') ?></strong></header>
        <div
            style="text-align: center; padding: 2rem; border: 2px dashed var(--pico-muted-border-color); color: var(--pico-muted-color);">
            <i class="fa-solid fa-cubes fa-2x"></i><br>
            <?= lang('Component.component_config_soon') ?>
        </div>
    </article>
</div>

<?= $this->endSection() ?>