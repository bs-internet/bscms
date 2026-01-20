<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>Kategoriler:
            <?= esc($contentType->title) ?>
        </h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/categories/<?= $contentType->id ?>/create" role="button" class="primary">
            <i class="fa-solid fa-plus"></i> Yeni Kategori
        </a>
    </div>
</div>

<article>
    <figure>
        <table>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">İsim</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Üst Kategori</th>
                    <th scope="col">Sıra</th>
                    <th scope="col" style="text-align: right;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Kayıt bulunamadı.</td>
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
                                        hx-confirm="Bu kategoriyi ve alt kategorilerini silmek istediğinize emin misiniz?"
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