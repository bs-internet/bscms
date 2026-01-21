<?= $this->extend('App\Core\Shared\Views\layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1><?= lang('Form.new_form') ?></h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/forms" role="button" class="secondary outline">
            <i class="fa-solid fa-arrow-left"></i> <?= lang('Admin.back') ?>
        </a>
    </div>
</div>

<article>
    <form action="/admin/forms/store" method="post">
        <?= csrf_field() ?>

        <div class="grid">
            <div style="grid-column: span 2;">
                <label for="title"><?= lang('Form.form_title') ?></label>
                <input type="text" id="title" name="title" required value="<?= old('title') ?>"
                    placeholder="<?= lang('Form.form_title') ?>">

                <label for="form_key"><?= lang('Form.form_key') ?></label>
                <input type="text" id="form_key" name="form_key" required value="<?= old('form_key') ?>"
                    placeholder="iletisim_formu">
                <small><?= lang('Form.form_help') ?></small>
            </div>

            <div>
                <article class="secondary-box">
                    <header><strong><?= lang('Admin.info') ?></strong></header>
                    <p><?= lang('Form.form_fields_help') ?></p>

                    <button type="submit" class="primary" style="width: 100%;">
                        <i class="fa-solid fa-save"></i> <?= lang('Admin.create') ?>
                    </button>
                </article>
            </div>
        </div>
    </form>
</article>

<?= $this->endSection() ?>