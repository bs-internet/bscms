<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1><?= lang('Category.title_with_type', [esc($contentType->title)]) ?></h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/categories/<?= $contentType->id ?>/create" role="button" class="primary">
            <i class="fa-solid fa-plus"></i> <?= lang('Category.new_category') ?>
        </a>
    </div>
</div>

<article>
    <figure>
        <table>
            <thead>
                <tr>
                    <th scope="col"><?= lang('Category.id') ?></th>
                    <th scope="col"><?= lang('Category.name') ?></th>
                    <th scope="col"><?= lang('Category.slug') ?></th>
                    <th scope="col"><?= lang('Category.parent_category') ?></th>
                    <th scope="col"><?= lang('Category.sort_order') ?></th>
                    <th scope="col" style="text-align: right;"><?= lang('Category.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;"><?= lang('Category.no_records') ?></td>
                    </tr>
                <?php else: ?>
                    <?php
                    // Simple recursive display function could go here, but for flat table:
                    foreach ($categories as $category):
                        ?>
                        <tr>
                            <td>
                                <?= $category->id ?>
                            </td>
                            <td>
                                <?= esc($category->name) ?>
                            </td>
                            <td>
                                <?= esc($category->slug) ?>
                            </td>
                            <td>
                                <?= $category->parent_id ?? '-' ?>
                            </td>
                            <td>
                                <?= $category->sort_order ?>
                            </td>
                            <td style="text-align: right;">
                                <div role="group">
                                    <a href="/admin/categories/<?= $contentType->id ?>/edit/<?= $category->id ?>" role="button"
                                        class="secondary outline btn-sm">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="#" hx-get="/admin/categories/<?= $contentType->id ?>/delete/<?= $category->id ?>"
                                        hx-confirm="<?= lang('Category.delete_confirm') ?>" role="button"
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