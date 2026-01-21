<?= $this->extend('App\Core\Shared\Views\layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1><?= lang('Form.title') ?></h1>
        <small><?= lang('Admin.forms') ?></small>
    </div>
    <div style="text-align: right;">
        <a href="/admin/forms/create" role="button" class="primary">
            <i class="fa-solid fa-plus"></i> <?= lang('Form.new_form') ?>
        </a>
    </div>
</div>

<article>
    <figure>
        <table>
            <thead>
                <tr>
                    <th scope="col"><?= lang('Form.id') ?></th>
                    <th scope="col"><?= lang('Form.form_title') ?></th>
                    <th scope="col"><?= lang('Form.form_key') ?></th>
                    <th scope="col" style="text-align: center;"><?= lang('Form.submissions') ?></th>
                    <th scope="col" style="text-align: right;"><?= lang('Form.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($forms)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;"><?= lang('Form.no_records') ?></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($forms as $form): ?>
                        <tr>
                            <td>
                                <?= $form->id ?>
                            </td>
                            <td>
                                <?= esc($form->title) ?>
                            </td>
                            <td><code><?= esc($form->form_key) ?></code></td>
                            <td style="text-align: center;">
                                <a href="/admin/forms/<?= $form->id ?>/submissions" role="button" class="outline btn-sm">
                                    <?= lang('Admin.view') ?>
                                </a>
                            </td>
                            <td style="text-align: right;">
                                <div role="group">
                                    <a href="/admin/forms/edit/<?= $form->id ?>" role="button" class="secondary outline btn-sm">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="#" hx-get="/admin/forms/delete/<?= $form->id ?>"
                                        hx-confirm="<?= lang('Form.delete_confirm') ?>" role="button"
                                        class="contrast outline btn-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </figure>
</article>

<?= $this->endSection() ?>