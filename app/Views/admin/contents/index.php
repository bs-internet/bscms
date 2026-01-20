<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="grid">
    <div>
        <h1>
            <?= esc($contentType->title) ?>
        </h1>
    </div>
    <div style="text-align: right;">
        <a href="/admin/contents/<?= $contentType->id ?>/create" role="button" class="primary">
            <i class="fa-solid fa-plus"></i> Yeni Ekle
        </a>
    </div>
</div>

<article>
    <div class="grid">
        <input type="search" name="search" placeholder="İçerik ara..." hx-get="/admin/contents/<?= $contentType->id ?>"
            hx-trigger="keyup changed delay:500ms" hx-target="#contents-table" hx-select="#contents-table">
    </div>

    <figure>
        <table id="contents-table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Başlık</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Durum</th>
                    <th scope="col">Tarih</th>
                    <th scope="col" style="text-align: right;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contents)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Kayıt bulunamadı.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contents as $content): ?>
                        <tr>
                            <td>
                                <?= $content->id ?>
                            </td>
                            <td>
                                <?= esc($content->title) ?>
                            </td>
                            <td>
                                <?= esc($content->slug) ?>
                            </td>
                            <td>
                                <?php
                                $statusColor = match ($content->status) {
                                    'published' => 'background-color: var(--pico-ins-color); color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8em;',
                                    'draft' => 'background-color: var(--pico-muted-color); color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8em;',
                                    'archived' => 'background-color: var(--pico-mark-background-color); color: var(--pico-mark-color); padding: 2px 8px; border-radius: 4px; font-size: 0.8em;',
                                    default => ''
                                };
                                $statusLabel = match ($content->status) {
                                    'published' => 'Yayında',
                                    'draft' => 'Taslak',
                                    'archived' => 'Arşiv',
                                    default => $content->status
                                };
                                ?>
                                <span style="<?= $statusColor ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </td>
                            <td>
                                <?= date('d.m.Y', strtotime($content->created_at)) ?>
                            </td>
                            <td style="text-align: right;">
                                <div role="group">
                                    <a href="/admin/contents/<?= $contentType->id ?>/edit/<?= $content->id ?>" role="button"
                                        class="secondary outline btn-sm">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="#" hx-get="/admin/contents/<?= $contentType->id ?>/delete/<?= $content->id ?>"
                                        hx-confirm="Bu içeriği silmek istediğinize emin misiniz?" role="button"
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