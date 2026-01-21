<?= $this->extend('App\Core\Shared\Views\layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1><?= lang('Form.edit_form') ?>:
            <?= esc($form->title) ?>
        </h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/forms" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> <?= lang('Admin.back') ?>
        </a>
    </div>
</div>

<div class="grid">
    <!-- Form Settings -->
    <article>
        <header><strong><?= lang('Admin.settings') ?></strong></header>
        <form action="/admin/forms/update/<?= $form->id ?>" method="post">
            <?= csrf_field() ?>

            <label for="title"><?= lang('Form.form_title') ?></label>
            <input type="text" id="title" name="title" required value="<?= old('title', $form->title) ?>">

            <label for="form_key"><?= lang('Form.form_key') ?></label>
            <input type="text" id="form_key" name="form_key" required value="<?= old('form_key', $form->form_key) ?>">

            <button type="submit" class="primary"><?= lang('Admin.update') ?></button>
        </form>
    </article>

    <!-- Form Fields (Placeholder for future implementation) -->
    <article>
        <header><strong><?= lang('Form.fields') ?></strong></header>
        <p><?= lang('Form.fields_help_placeholder') ?></p>

        <div
            style="text-align: center; padding: 2rem; border: 2px dashed var(--pico-muted-border-color); color: var(--pico-muted-color);">
            <i class="fa-solid fa-wrench fa-2x"></i><br>
            <?= lang('Form.field_management_soon') ?>
        </div>
    </article>
</div>

<?= $this->endSection() ?>