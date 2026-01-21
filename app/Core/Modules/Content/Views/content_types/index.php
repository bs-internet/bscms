<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1><?= lang('Content.content_types') ?></h1>
        <small><?= lang('Content.description') ?></small>
    </div>
    <div style="text-align: right;">
        <a href="/admin/content-types/create" role="button" class="primary">
            <i class="fa-solid fa-plus"></i> <?= lang('Content.new_content_type') ?>
        </a>
    </div>
</div>

<article>
    <figure>
        <table>
            <thead>
                <tr>
                    <th scope="col"><?= lang('Content.id') ?></th>
                    <th scope="col"><?= lang('Content.content_type_title') ?></th>
                    <th scope="col"><?= lang('Content.content_type_slug') ?></th>
                    <th scope="col"><?= lang('Admin.status') ?></th>
                    <th scope="col" style="text-align: right;"><?= lang('Content.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contentTypes)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;"><?= lang('Content.no_records') ?></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contentTypes as $type): ?>
                        <tr>
                            <td>
                                <?= $type->id ?>
                            </td>
                            <td>
                                <?= esc($type->name) ?>
                            </td>
                            <td><code><?= esc($type->slug) ?></code></td>
                            <td>
                                <?php if ($type->has_categories): ?> <span data-tooltip="Kategori Desteği"><i
                                            class="fa-solid fa-tags"></i></span>
                                <?php endif; ?>
                                <?php if ($type->has_seo_fields): ?> <span data-tooltip="SEO Alanları"><i
                                            class="fa-solid fa-magnifying-glass"></i></span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <div role="group">
                                    <a href="/admin/content-types/edit/<?= $type->id ?>" role="button"
                                        class="secondary outline btn-sm">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="#" hx-get="/admin/content-types/delete/<?= $type->id ?>"
                                        hx-confirm="Bu içerik türünü silmek istediğinize emin misiniz? Buna bağlı TÜM içerikler silinecektir!"
                                        role="button" class="contrast outline btn-sm">
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