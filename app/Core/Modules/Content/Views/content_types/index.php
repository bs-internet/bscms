<?= $this->extend('App\Core\Shared\Views\layout\master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>İçerik Türleri</h1>
        <small>Sistemdeki içerik yapılarını (Blog, Haber vb.) yönetin.</small>
    </div>
    <div style="text-align: right;">
        <a href="/admin/content-types/create" role="button" class="primary">
            <i class="fa-solid fa-plus"></i> Yeni Tür Ekle
        </a>
    </div>
</div>

<article>
    <figure>
        <table>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">İsim (Name)</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Özellikler</th>
                    <th scope="col" style="text-align: right;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contentTypes)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Kayıt bulunamadı.</td>
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
